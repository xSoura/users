export default defineNuxtRouteMiddleware(async (to, from) => {
    const token = useCookie('token').value;
    if (!token) {
      return navigateTo('/');
    }
    try {
      await $fetch('http://127.0.0.1:8000/api/validate-token', {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });  
    } catch (error) {
      return navigateTo('/');
    }
  });
  