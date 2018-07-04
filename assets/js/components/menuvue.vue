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
        <b-form @submit="onSubmit">
            <b-form-group label="Nazwa zakładki:"  label-for="name" description="Podana nazwa zostanie wyświetlona na stronie">
                <b-form-input v-model="menu.name" type="text" id="name" required placeholder="Wpisz nazwe zakładki"></b-form-input>
            </b-form-group>
            <b-form-group label="Strona:"  label-for="name">
                <b-form-select v-model="menu.page" :options="options" id="name" required></b-form-select>
            </b-form-group>
            <b-form-group label="Otwórz w nowej karcie:"  label-for="type">
                <b-form-select v-model="menu.new_tab" :options="options_type" id="type" required></b-form-select>
            </b-form-group>
            <b-form-group>
                <b-form-checkbox v-model="menu.active" unchecked-value="false" value="true">Zakładka aktywna</b-form-checkbox>
            </b-form-group>
            <b-button type="submit" variant="primary">Dodaj zakładkę</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        mounted: function () {
            this.getPages();
        },
        data () {
            return {
                options: [],
                options_type: [
                    { value: 'false', text: 'Nie'},
                    { value: 'true', text: 'Tak' }
                ],
                menu: {
                    page: '',
                    name: '',
                    new_tab: 'false',
                    active: 'true'
                },
                status:{
                    fail: null,
                    success: null,
                    message: [],
                }
            }
        },
        methods: {
            onSubmit: function (event) {
                event.preventDefault();
                this.status.fail = null;
                this.status.success = null;
                let form = new FormData();
                let self = this;
                form.append('page', this.menu.page);
                form.append('name', this.menu.name);
                form.append('new_tab', this.menu.new_tab);
                form.append('active', this.menu.active);
                axios.post(window.location.href, form).then(function(response){
                    if(response.data.status === true)
                    {
                        self.status.success = true;
                        self.status.message = response.data.message;
                        self.onRestart();
                    }
                    else
                    {
                        self.status.fail = true;
                        self.status.message = response.data.message;
                    }
                });
            },
            onRestart: function () {
                this.menu = {
                    page: this.options[0].id,
                    name: '',
                    new_tab: false,
                    active: true
                };
            },
            getPages: function () {
                let self = this;
                axios.get('/admin/api/page/').then(function(response) {
                    let data = JSON.parse(response.data);
                    for(let x=0;x<data.length;x++)
                    {
                        self.options.push({
                            value: data[x].id,
                            text: data[x].name
                        });
                    }
                    self.menu.page = data[0].id;
                });
            }
        }
    }
</script>

<style scoped>
.div{
    margin-top: 15px;
}
</style>