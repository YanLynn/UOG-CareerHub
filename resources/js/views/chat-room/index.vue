<template>
    <div class="p-4 h-screen max-w-6xl mx-auto flex flex-col sm:flex-row gap-4">
        <!-- Sidebar (Contacts) -->
        <div class="w-full sm:w-1/3 bg-white dark:bg-gray-800 rounded-xl shadow p-4 space-y-4 overflow-y-auto">
            <h3 class="text-lg font-bold text-blue-600 dark:text-blue-300">Contacts</h3>
            <div v-for="contact in contacts" :key="contact.id" @click="selectContact(contact)"
                class="p-3 rounded-lg cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-700"
                :class="{ 'bg-gray-100 dark:bg-gray-700': selectedContact?.id === contact.id }">
                <div class="flex items-center gap-3">
                    <Avatar :label="contact.name[0]" size="large" shape="circle" />
                    <div class="flex flex-col">
                        <p class="font-semibold text-gray-800 dark:text-white">{{ contact.name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ contact.lastMessage }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Window -->
        <div class="w-full sm:w-2/3 bg-white dark:bg-gray-900 rounded-xl shadow flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b dark:border-gray-700 flex items-center gap-3">
                <Avatar :label="selectedContact?.name[0]" shape="circle" />
                <div class="flex flex-col">
                    <h4 class="font-bold text-gray-800 dark:text-white">
                        {{ selectedContact?.name || 'Select a contact' }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ isTyping ? 'Typing...' : 'Online' }}
                    </p>
                </div>
            </div>

            <!-- Messages -->
            <div ref="chatBox" class="flex-1 overflow-y-auto p-4 space-y-4" @scroll.passive="handleScroll">
                <div v-for="(msg, i) in selectedContact?.messages || []" :key="i"
                    :class="msg.from === 'me' ? 'text-right' : 'text-left'">
                    <div class="inline-block px-4 py-2 max-w-xs rounded-2xl" :class="msg.from === 'me'
                        ? 'bg-blue-500 text-white rounded-br-none'
                        : 'bg-gray-200 dark:bg-gray-700 dark:text-white rounded-bl-none'">
                        {{ msg.text }}
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ msg.time }}</p>
                </div>
            </div>

            <!-- Input -->
            <div class="p-4 border-t dark:border-gray-700 flex items-center gap-2">
                <InputText v-model="newMessage" placeholder="Type a message..." class="flex-1" />
                <Button icon="pi pi-send" @click="sendMessage" :disabled="!newMessage.trim()" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import Avatar from 'primevue/avatar'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { useAuthStore } from '@/store'

const authStore = useAuthStore()
const contacts = ref([])
const selectedContact = ref(null)
const newMessage = ref('')
const isTyping = ref(false)
const typingTimeout = ref(null)
const chatBox = ref(null)
const role = ref()
const scrollToBottom = () => {
    nextTick(() => {
        if (chatBox.value) {
            chatBox.value.scrollTop = chatBox.value.scrollHeight
        }
    })
}

onMounted(async () => {
    try {
        contacts.value = await authStore.getContacts()
        console.log('aa',authStore.currentUser.role)
         role.value = authStore.currentUser.role
        selectedContact.value = contacts.value[0] || null

        if (selectedContact.value) {
            selectedContact.value.messages = await authStore.getMessages(selectedContact.value.id)

            if (typeof window.Echo !== 'undefined') {
                window.Echo.private(`chat.${selectedContact.value.id}`)
                    .listen('.MessageSent', (e) => {
                        selectedContact.value.messages.push({
                            from: e.sender_type === role ? 'me' : 'them',
                            text: e.message,
                            time: e.created_at
                        })
                        scrollToBottom()
                    })
                    .listen('.UserTyping', (e) => {
                        isTyping.value = e.typing
                    })
            } else {
                console.warn('⚠️ Echo is not defined. Make sure it is initialized')
            }
        }
    } catch (error) {
        console.error('Error initializing chat:', error)
    }
})

const selectContact = async (contact) => {
    selectedContact.value = contact
    if (!contact.messages) {
        selectedContact.value.messages = await authStore.getMessages(contact.id)
    }
}

const sendMessage = async () => {
    if (!newMessage.value.trim()) return

    const message = {
        from: 'me', // used only for UI display
        text: newMessage.value,
        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    }

    selectedContact.value.messages.push(message)
    scrollToBottom()

    try {
        await authStore.sendMessage(selectedContact.value.id, {
            sender_type: role.value?.toLowerCase(), // ✅ this gets stored in DB correctly
            text: message.text,
        })
    } catch (error) {
        console.error('Failed to send message:', error)
    }

    newMessage.value = ''
}

watch(newMessage, async () => {
    if (selectedContact.value) {
        await authStore.sendTyping(selectedContact.value.id, true)

        clearTimeout(typingTimeout.value)
        typingTimeout.value = setTimeout(() => {
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
