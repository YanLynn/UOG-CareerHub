

// useEchoListener.js
import Echo from 'laravel-echo';




export default function useEchoListener(chatRoomId, currentUserId, {

    onMessageReceived = () => {},
    onTypingStart = () => {},
    onTypingStop = () => {},
    onReadReceipt = () => {}
  }) {
    console.log('aaaaa',window.Echo)
    if (!window.Echo || !chatRoomId) return
    const channel = window.Echo.private(`chat.${chatRoomId}`)

    channel.listen('.MessageSent', (event) => {
        console.log('aaaaa',event)
      if (event.message.sender_id !== currentUserId) {
        console.log('a',currentUserId,chatRoomId)
        onMessageReceived(event.message)
      }
    })

    channel.listen('.UserTyping', (event) => {
      if (event.typing && event.user_id !== currentUserId) onTypingStart(event)
      if (!event.typing && event.user_id !== currentUserId) onTypingStop(event)
    })

    channel.listen('.MessageRead', (event) => {
      if (event.user_id !== currentUserId) onReadReceipt(event)
    })

    return () => {
      channel.stopListening('.MessageSent')
      channel.stopListening('.UserTyping')
      channel.stopListening('.MessageRead')
    }
  }
