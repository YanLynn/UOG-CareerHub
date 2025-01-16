import { defineStore } from 'pinia';

export const useToastStore = defineStore('toast', {
  state: () => ({
    isVisible: false,
    message: '',
    type: 'success', // success | error | info
  }),
  actions: {
    showToast(message, type = 'success') {
      this.message = message;
      this.type = type;
      this.isVisible = true;

      // Auto-hide after 3 seconds
      setTimeout(() => {
        this.isVisible = false;
      }, 3000);
    },
    hideToast() {
      this.isVisible = false;
    },
  },
});
