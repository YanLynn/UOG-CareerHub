<template>
    <div class="p-4 max-w-6xl mx-auto flex flex-col sm:flex-row gap-4 min-h-screen">
        <!-- Sidebar (Contacts) -->
        <div class="w-full sm:w-1/3 bg-white shadow-md rounded-xl p-4 overflow-y-auto">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Chat Contacts</h2>
            <div v-for="contact in contacts" :key="contact.id" @click="selectContact(contact)"
                class="cursor-pointer p-3 rounded-lg transition-all hover:bg-gray-100 relative"
                :class="{ 'bg-blue-50': selectedContact?.id === contact.id }">
                <div class="flex items-center gap-3">
                    <Avatar v-if="contact.avatar && contact.avatar.startsWith('http')" :image="contact.avatar"
                        size="large" shape="circle" />
                    <Avatar v-else :label="contact.name[0]" size="large" shape="circle" />
                    <div>
                        <p class="font-semibold">{{ contact.name }}</p>
                        <p class="text-xs text-gray-500">{{ contact.messages.at(-1)?.text || 'No messages yet' }}</p>
                    </div>
                </div>
                <Badge v-if="contact.unreadCount" :value="contact.unreadCount" severity="info"
                    class="absolute top-2 right-2" />
            </div>
        </div>

        <!-- Chat Window -->
        <div class="w-full sm:w-2/3 bg-white shadow-md rounded-xl flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 flex items-center gap-3">
                <div class="relative">
                    <Avatar v-if="selectedContact?.avatar && selectedContact.avatar.startsWith('http')"
                        :image="selectedContact.avatar" shape="circle" />
                    <Avatar v-else :label="selectedContact?.name[0] || '?'" shape="circle" />
                    <span v-if="selectedContact?.isOnline"
                        class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-white border-2 rounded-full" />
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">{{ selectedContact?.name || 'Select a contact' }}</h4>
                    <p class="text-sm text-gray-500">
                        {{ isTyping ? 'Typing...' : selectedContact?.statusText || 'Offline' }}
                    </p>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 relative" ref="chatBox" @scroll.passive="handleScroll">
                <div v-for="(msg, i) in selectedContact?.messages || []" :key="i"
                    :class="msg.from === 'me' ? 'text-right' : 'text-left'">
                    <div class="inline-block px-4 py-2 max-w-xs rounded-xl group relative"
                        :class="msg.from === 'me' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none'">
                        {{ msg.text }}
                        <div v-if="msg.from === 'me' && msg.editable"
                            class="absolute right-0 top-0 hidden group-hover:flex gap-1">
                            <Button icon="pi pi-pencil" class="p-button-text p-button-sm" @click="editMessage(i)" />
                            <Button icon="pi pi-trash" class="p-button-text p-button-sm" @click="deleteMessage(i)" />
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ msg.time }}</p>
                </div>

                <div v-if="showScrollToast" class="absolute bottom-16 left-1/2 transform -translate-x-1/2">
                    <Button label="New Message" icon="pi pi-arrow-down" @click="scrollToBottom" />
                </div>
            </div>

            <!-- Input -->
            <div class="p-4 border-t border-gray-200 flex gap-2">
                <InputText v-model="newMessage" placeholder="Type a message..." class="flex-1"
                    @keydown.enter.exact.prevent="sendMessage" @keydown.enter.shift="insertNewLine" />
                <Button icon="pi pi-send" @click="sendMessage" :disabled="!newMessage.trim()" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, nextTick, onMounted, watch } from 'vue'
import Avatar from 'primevue/avatar'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import Badge from 'primevue/badge'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/store'
import useEchoListener from '../chat-room/EchoListener'

const authStore = useAuthStore()
const route = useRoute()
const chatContacts = route.query

const params = {
    jobseeker_id: chatContacts.jobseeker_id,
    job_id: chatContacts.job_id
}

const contacts = ref([])
const selectedContact = ref(null)
const newMessage = ref('')
const isTyping = ref(false)
const showScrollToast = ref(false)
const chatBox = ref(null)
let typingTimeout

const selectContact = (contact) => {
    console.log('contact',contact)
    selectedContact.value = contact
    nextTick(() => scrollToBottom())
}

const sendMessage = async () => {
    if (!newMessage.value.trim()) return

    const tempMessage = {
        from: 'me',
        text: newMessage.value,
        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        editable: true,
        read: false
    }

    selectedContact.value.messages.push(tempMessage)
    scrollToBottom()

    try {
        await authStore.sendMessage(selectedContact.value.id, { text: newMessage.value, employerID :selectedContact.value.employer.id, jobSeekerID : selectedContact.value.jobSeeker.id })
    } catch (error) {
        console.error('Failed to send message:', error)
    }

    newMessage.value = ''
}

const scrollToBottom = () => {
    nextTick(() => {
        if (chatBox.value) {
            chatBox.value.scrollTop = chatBox.value.scrollHeight
            showScrollToast.value = false
        }
    })
}

const handleScroll = () => {
    if (!chatBox.value) return
    const nearBottom = chatBox.value.scrollHeight - chatBox.value.scrollTop - chatBox.value.clientHeight < 100
    showScrollToast.value = !nearBottom
}

const editMessage = (index) => {
    alert(`Edit message at index ${index}`)
}

const deleteMessage = (index) => {
    selectedContact.value.messages.splice(index, 1)
}

const insertNewLine = () => {
    newMessage.value += '\n'
}

const handleNewMessage = (message) => {
    const isMe = message.sender_type === authStore.currentUser.role && message.sender_id === authStore.currentUser.id
    selectedContact.value.messages.push({
        from: isMe ? 'me' : 'them',
        text: message.message,
        time: new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    })
    scrollToBottom()
}

const handleTypingStart = () => { isTyping.value = true }
const handleTypingStop = () => { isTyping.value = false }

onMounted(async () => {
    try {
        const res = await authStore.getContacts(params)
        contacts.value = res
        const found = contacts.value.find(c => String(c.jobseeker_id) === String(params.jobseeker_id))
        selectedContact.value = found || contacts.value[0] || null

        if (selectedContact.value?.id) {
            useEchoListener(
                selectedContact.value.id,
                authStore.currentUser.id,
                {
                    onMessageReceived: handleNewMessage,
                    onTypingStart: handleTypingStart,
                    onTypingStop: handleTypingStop
                }
            )
        }

        nextTick(() => scrollToBottom())
    } catch (err) {
        console.error('Failed to load contacts:', err)
    }
})

watch(newMessage, () => {
    if (selectedContact.value) {
        authStore.sendTyping(selectedContact.value.id, true)
        clearTimeout(typingTimeout)
        typingTimeout = setTimeout(() => {
            authStore.sendTyping(selectedContact.value.id, false)
        }, 1500)
    }
})
</script>

<style scoped>
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
}
</style>
