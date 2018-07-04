<template>
    <div class="div">
        <div v-if="status.success">
            <b-alert show dismissible variant="success">
                <div v-for="message in status.message">{{ message }}</div>
            </b-alert>
        </div>

        <div v-if="status.fail">
            <b-alert show dismissible variant="danger">
                <div v-for="message in status.message">{{ message }}</div>
            </b-alert>
        </div>

        <b-form  @submit="onSubmit" @reset="onReset">
            <b-form-group label="Nazwa strony:" label-for="page_name">
                <b-form-input id="page_name" type="text" v-model="form.name" required placeholder="Wpisz nazwę strony.."></b-form-input>
            </b-form-group>
            <b-form-group label="Typ strony:" label-for="type_page">
                <b-form-select id="type_page" :options="options" v-model="form.type"></b-form-select>
            </b-form-group>
            <b-form-group v-if="form.type === 'inside'">
                <vue-ckeditor v-model="form.page" :config="config"></vue-ckeditor>
            </b-form-group>
            <b-form-group v-if="form.type === 'outside'" label="Link do strony:" label-for="page">
                <b-form-input id="page" v-model="form.page" type="text" required placeholder="Wpisz link strony.."></b-form-input>
            </b-form-group>
            <b-button type="submit" variant="primary">Zapisz stronę</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                config: {
                    height: 400,
                    extraPlugins: 'codesnippet',
                    codeSnippet_theme: 'pojoaque'
                },
                options: [
                    { value: 'inside', text: 'Wewnętrzna' },
                    { value: 'outside', text: 'Zewnętrzna' }
                ],
                form: {
                    name: '',
                    page: '',
                    type: 'inside'
                },
                status: {
                    success: false,
                    fail: false,
                    message: ''
                },
            }
        },
        methods: {
            onSubmit: function (event) {
                event.preventDefault();
                this.status.fail = false;
                this.status.success = false;
                let form = new FormData();
                let self = this;
                form.append('name', this.form.name);
                form.append('page', this.form.page);
                form.append('type', this.form.type);

                axios.post(window.location.href, form).then(function(response){
                    if(response.data.status === true)
                    {
                        self.status.success = true;
                        self.status.message = response.data.message;
                        self.onReset();
                    }
                    else
                    {
                        self.status.fail = true;
                        self.status.message = response.data.message;
                    }
                });
            },
            onReset: function () {
                this.form.name = '';
                this.form.page = '';
                this.status.fail = false;
            }
        },
    }
</script>

<style scoped>
    .div{
        margin-top: 15px;
    }
</style>