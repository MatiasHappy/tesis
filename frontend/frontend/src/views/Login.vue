
<template>

    <div class="flex bg-loginBg justify-center flex-1 flex-col  px-6 py-8 lg:px-8">
     
      <Logo2 class="m-auto my-12 w-full" />
     
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <!--<img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />-->
        <MainH1 class="font-SourceSans text-center text-xl leading-9 font-black tracking-tight text-duty">SIGN IN TO YOUR ACCOUNT</MainH1>
      </div>
     
      <div class="mt-4 sm:mx-auto sm:w-full sm:max-w-sm px-2">
        <form @submit.prevent="login" class="space-y-6" >
          <div class="relative">
            <label for="email" class="hidden block text-md font-medium leading-6 text-duty">Email address</label>
            <div class="mt-2">
              <input v-model="email" id="email" name="email" type="email" autocomplete="email" placeholder="Email Address" required class="block px-2 w-full rounded-md border-0 py-1.5 text-dutyLight font-SourceSans font-semibold shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-dutyLight/50 focus:ring-2 focus:ring-inset focus:ring-habit sm:text-sm sm:leading-6" />
            </div>
          </div>
  
          <div>
            <div class=" flex items-center justify-between">
              <label for="password" class="hidden block text-md font-medium leading-6 text-duty">Password</label>
              
            </div>
            <div class="mt-2">
              <input v-model="password" id="password" name="password" type="password" placeholder="Password" autocomplete="current-password" required="" class="block px-2 w-full rounded-md border-0 py-1.5 text-dutyLight font-SourceSans font-semibold shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-dutyLight/50 focus:ring-2 focus:ring-inset focus:ring-habit sm:text-sm sm:leading-6" />
            </div>
            <div class="text-sm ml-auto flex items-end justify-end mt-2 font-SourceSans text-grey-300">
              <a href="#" class="font-semibold text-gray-400 hover:text-indigo-500">Forgot password?</a>
            </div>
          </div>
  
          <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-duty px-3 py-2 text-xl font-semibold leading-6 text-white shadow-sm hover:bg-fun focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-fun tracking-widest font-NovecentoCondRegular">Sign in</button>
          </div>
        </form>
  
        <p class="mt-10 font-SourceSans text-center text-sm text-gray-500">
          Not a member?
          {{ ' ' }}
          <a href="#" class="font-semibold leading-6 text-duty hover:text-fun">Register here</a>
        </p>
      </div>
    </div>
  </template>
  


<script>
import Logo2 from '../components/partials/Logo2.vue';

export default {
  components: {
    Logo2,
    
  },
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
<style>
/* Standard syntax */
input::placeholder {
  color: #256f77;
}

/* Mozilla Firefox */
input:-moz-placeholder {
  color: #256f77;
}

/* Internet Explorer 10+ */
input:-ms-input-placeholder {
  color: #256f77;
}

/* Microsoft Edge */
input::-ms-input-placeholder {
  color: #256f77;
}

/* Older WebKit browsers (e.g., Safari, Chrome) */
input::-webkit-input-placeholder {
  color: #256f77;
}
</style>
