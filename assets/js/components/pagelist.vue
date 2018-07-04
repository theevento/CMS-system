<template>
    <div class="main__div">
        <b-row>
            <b-col>
                <b-form-group  label="Wyszukaj strone:" class="text-center">
                    <b-input-group>
                        <b-form-input v-model="filter" placeholder="Nazwa strony.."></b-form-input>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col>
                <b-form-group label="Ilość elementów na stronę:" class="text-center">
                    <b-form-select :options="pageOptions" v-model="perPage"></b-form-select>
                </b-form-group>
            </b-col>
        </b-row>
        <b-table :filter="filter" :current-page="currentPage" :per-page="perPage" responsive striped hover :items="data_page    " :fields="fields">
            <span slot="type" slot-scope="row">{{row.value === 'inside' ? 'Wewnętrzny':'Zewntęrzny'}}</span>
            <template slot="actions" slot-scope="row">
                <b-btn v-b-modal.modalInfo size="sm" @click.stop="info(row.item)" class="mr-1">
                    Edytuj
                </b-btn>
            </template>
        </b-table>
        <b-pagination class="justify-content-md-center" :total-rows="totalRows" :per-page="perPage" v-model="currentPage" ></b-pagination>
        <b-modal size="lg" id="modalInfo" :title="'Edycja strony'" ok-only>
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
                <b-form-group label="Nazwa strony:" label-for="page_name">
                    <b-form-input id="page_name" type="text" v-model="page.name" required placeholder="Wpisz nazwę strony.."></b-form-input>
                </b-form-group>
                <b-form-group label="Typ strony:" label-for="type_page">
                    <b-form-select id="type_page" :options="options" v-model="page.type"></b-form-select>
                </b-form-group>
                <b-form-group v-if="page.type === 'inside'">
                    <vue-ckeditor v-model="page.page" :config="config"></vue-ckeditor>
                </b-form-group>
                <b-form-group v-if="page.type === 'outside'" label="Link do strony:" label-for="page">
                    <b-form-input id="page" v-model="page.page" type="text" required placeholder="Wpisz link strony.."></b-form-input>
                </b-form-group>
                <b-button type="submit" variant="primary">Aktualizuj stronę</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        mounted: function () {
            this.getPages();
        },
        data () {
            return {
                config: {
                    height: 150,
                    extraPlugins: 'codesnippet',
                    codeSnippet_theme: 'pojoaque'
                },
                options: [
                    { value: 'inside', text: 'Wewnętrzna' },
                    { value: 'outside', text: 'Zewnętrzna' }
                ],
                data_article: [],
                totalRows: 0,
                perPage: 10,
                pageOptions: [ 5, 10, 15 ],
                currentPage: 1,
                filter: '',
                data_page: [],
                fields: {
                    name: {
                        label: 'Nazwa',
                        'class': 'text-center',
                        sortable: true
                    },
                    type: {
                        label: "Typ",
                        'class': 'text-center'
                    },
                    actions: {
                        label: 'Działania',
                        'class': 'text-center'
                    }
                },
                page: {
                    name: null,
                    page: null,
                    id: null,
                    type: 'inside'
                },
                status:{
                    fail: null,
                    success: null,
                    message: [],
                }
            }
        },
        methods: {
            info (content) {
                this.page.name = content.name;
                this.page.page = content.page;
                this.page.id = content.id;
                this.page.type = content.type;
            },
            onSubmit: function (event) {
                event.preventDefault();
                this.status.fail = null;
                this.status.success = null;
                let form = new FormData();
                let self = this;
                form.append('name', this.page.name);
                form.append('page', this.page.page);
                form.append('id', this.page.id);
                form.append('type', this.page.type);

                axios.post(window.location.href, form).then(function(response){
                    if(response.data.status === true)
                    {
                        self.status.success = true;
                        self.status.message = response.data.message;
                        self.getPages();
                    }
                    else
                    {
                        self.status.fail = true;
                        self.status.message = response.data.message;
                    }
                });
            },
            getPages: function () {
                let self = this;
                axios.get('/admin/api/page/').then(function(response) {
                    let data = JSON.parse(response.data);
                    self.data_page = data;
                    self.totalRows = data.length;
                });
            }
        }
    }
</script>

<style scoped>
    .main__div
    {
        margin-top: 15px;
    }
</style>