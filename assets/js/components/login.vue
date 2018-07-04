<template>
    <b-form method="POST" action="/login">
        <div v-if="showerror == true">
            <b-alert variant="danger" show dismissible>
               Nieprawidłowy login lub hasło.
            </b-alert>
        </div>
        <b-form-group label="Login:" label-for="login">
            <b-form-input id="login" type="text" placeholder="Wpisz login.." name="_username"></b-form-input>
        </b-form-group>
        <b-form-group label="Hasło:" label-for="password">
            <b-form-input id="password" type="password" placeholder="Wpisz hasło.." name="_password"></b-form-input>
        </b-form-group>
        <b-button type="submit" variant="primary">Zaloguj się</b-button>
    </b-form>
</template>

<script>
    export default {
        props: ['showerror', 'username'],
    }
</script>

<style scoped>

</style>