<template>
    <div>
        <form @submit.prevent="register">
            <input v-model="name" type="text" placeholder="Name" required>
            <input v-model="email" type="email" placeholder="Email" required>
            <input v-model="password" type="password" placeholder="Password" required>
            <input v-model="password_confirmation" type="password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
        };
    },
    methods: {
        async register() {
            await this.$store.dispatch('register', {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
            });
            this.$router.push('/login');
        }
    }
};
</script>
