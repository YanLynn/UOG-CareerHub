

import { useAuthStore } from '@components/js/store';

export function useTokenRefresh() {
  const authStore = useAuthStore();

  const startTokenRefreshTimer = () => {
    if (!authStore.token) return;

    try {
      const jwtPayload = JSON.parse(atob(authStore.token.split('.')[1]));
      const expiresAt = jwtPayload.exp * 1000;
      const timeout = expiresAt - Date.now() - 60000;

      if (timeout > 0) {
        setTimeout(() => {
          authStore.tokenRefresh();
        }, timeout);
      }
    } catch (error) {
      console.error('Error in token refresh timer:', error);
      authStore.logout();
    }
  };

  return { startTokenRefreshTimer };
}
