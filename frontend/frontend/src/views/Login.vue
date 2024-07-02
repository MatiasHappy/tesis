
<template>

    <div class="flex  flex-1 flex-col  px-6 py-8 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <!--<img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />-->
        <MainH1 class="font-NovecentoCondBold text-center text-3xl font-bold leading-9 tracking-tight text-duty">Sign in to your account</MainH1>
      </div>
  
      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form @submit.prevent="login" class="space-y-6" >
          <div>
            <label for="email" class="block text-md font-medium leading-6 text-duty">Email address</label>
            <div class="mt-2">
              <input v-model="email" id="email" name="email" type="email" autocomplete="email" required class="block px-2 w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-habit sm:text-sm sm:leading-6" />
            </div>
          </div>
  
          <div>
            <div class="flex items-center justify-between">
              <label for="password" class="block text-md font-medium leading-6 text-duty">Password</label>
              <div class="text-sm">
                <a href="#" class="font-semibold text-black hover:text-indigo-500">Forgot password?</a>
              </div>
            </div>
            <div class="mt-2">
              <input v-model="password" id="password" name="password" type="password" autocomplete="current-password" required="" class="block px-2 w-full rounded-md border-0 py-1.5 text-black shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-habit sm:text-sm sm:leading-6" />
            </div>
          </div>
  
          <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-duty px-3 py-2 text-xl font-semibold leading-6 text-white shadow-sm hover:bg-fun focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-fun tracking-widest font-NovecentoCondRegular">Sign in</button>
          </div>
        </form>
  
        <p class="mt-10 text-center text-sm text-gray-500">
          Not a member?
          {{ ' ' }}
          <a href="#" class="font-semibold leading-6 text-duty hover:text-fun">Register here</a>
        </p>
      </div>
    </div>
  </template>
  


<script>
export default {
    data() {
        return {
            email: '',
            password: ''
        };
    },
    methods: {
        async login() {
            await this.$store.dispatch('login', { email: this.email, password: this.password });
            this.$router.push('/dashboard');
        }
    }
};
</script>
