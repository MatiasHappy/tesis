<template>
    <div>
        <form @submit.prevent="login">
            <input v-model="email" type="email" placeholder="Email" required>
            <input v-model="password" type="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
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
