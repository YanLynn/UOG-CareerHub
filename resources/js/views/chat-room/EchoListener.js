import Echo from 'laravel-echo'

export default function useEchoListener(chatRoomId, {
  onMessageReceived,
  onTypingStart,
  onTypingStop
}) {
  const echo = window.Echo.private(`chat.${chatRoomId}`)

  echo.listen('.MessageSent', (e) => {
    if (onMessageReceived) {
      onMessageReceived(e)
    }
  })

  echo.listen('.UserTyping', (e) => {
    if (e.typing && onTypingStart) onTypingStart(e)
    if (!e.typing && onTypingStop) onTypingStop(e)
  })
}
