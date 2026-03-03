import { toast } from 'vue3-toastify'

const pendingToasts = []

export function queueToastAfterLayout(message, options = { style: { zIndex: 100000 } }) {
  pendingToasts.push({ message, options })
}

export function flushToastQueue() {
  pendingToasts.forEach(({ message, options }) => {
    const content = typeof message === 'function' ? message() : message
    toast(content, {
      type: 'default',
      position: 'top-right',
      autoClose: 5000,
      ...options
    })
  })
  pendingToasts.length = 0
}
