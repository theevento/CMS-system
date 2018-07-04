<template>
    <div class="main__div">
        <b-row>
            <b-col>
                <b-form-group  label="Wyszukaj zakładkę:" class="text-center">
                    <b-input-group>
                        <b-form-input v-model="filter" placeholder="Nazwa zakładki.."></b-form-input>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col>
                <b-form-group label="Ilość elementów na stronę:" class="text-center">
                    <b-form-select :options="pageOptions" v-model="perPage"></b-form-select>
                </b-form-group>
            </b-col>
        </b-row>
        <b-table :filter="filter" :current-page="currentPage" :per-page="perPage" responsive striped hover :items="data_menu" :fields="fields">
            <span slot="new_tab" slot-scope="row">{{row.value === true ? 'Tak':'Nie'}}</span>
            <span slot="active" slot-scope="row">{{row.value === true ? 'Tak':'Nie'}}</span>
            <template slot="actions" slot-scope="row">
                <b-btn v-b-modal.modalInfo size="sm" @click.stop="info(row.item)" class="mr-1">
                    Edytuj
                </b-btn>
            </template>
        </b-table>
        <b-pagination class="justify-content-md-center" :total-rows="totalRows" :per-page="perPage" v-model="currentPage" ></b-pagination>
        <b-modal size="lg" id="modalInfo" :title="'Edycja zakładki'" ok-only>
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
                    <b-form-select v-model="menu.page_id" :options="options" id="name" required></b-form-select>
                </b-form-group>
                <b-form-group label="Otwórz w nowej karcie:"  label-for="type">
                    <b-form-select v-model="menu.new_tab" :options="options_type" id="type" required></b-form-select>
                </b-form-group>
                <b-form-group>
                    <b-form-checkbox v-model="menu.active" unchecked-value="false" value="true">Zakładka aktywna</b-form-checkbox>
                </b-form-group>
                <b-button type="submit" variant="primary">Aktualizuj zakładkę</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        mounted: function () {
            this.getMenu();
            this.getPages();
        },
        data () {
            return {
                config: {
                    height: 150,
                    extraPlugins: 'codesnippet',
                    codeSnippet_theme: 'pojoaque'
                },
                options: [],
                options_type: [
                    { value: 'false', text: 'Nie'},
                    { value: 'true', text: 'Tak' }
                ],
                totalRows: 0,
                perPage: 10,
                pageOptions: [ 5, 10, 15 ],
                currentPage: 1,
                filter: '',
                data_menu: [],
                fields: {
                    name: {
                        label: 'Nazwa',
                        'class': 'text-center',
                        sortable: true
                    },
                    new_tab: {
                        label: "Nowa karta",
                        'class': 'text-center'
                    },
                    active: {
                        label: 'Aktywne',
                        'class': 'text-center'
                    },
                    actions: {
                        label: 'Działania',
                        'class': 'text-center'
                    }
                },
                menu: {
                    id: '',
                    page_id: '',
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
            info (content) {
                this.menu = content;
            },
            onSubmit: function (event) {
                event.preventDefault();
                this.status.fail = null;
                this.status.success = null;
                let form = new FormData();
                let self = this;

                form.append('id', this.menu.id);
                form.append('page', this.menu.page_id);
                form.append('name', this.menu.name);
                form.append('new_tab', this.menu.new_tab);
                form.append('active', this.menu.active);

                axios.post(window.location.href, form).then(function(response){
                    if(response.data.status === true)
                    {
                        self.status.success = true;
                        self.status.message = response.data.message;
                        self.getMenu();
                    }
                    else
                    {
                        self.status.fail = true;
                        self.status.message = response.data.message;
                    }
                });
            },
            getMenu: function () {
                let self = this;
                axios.get('/admin/menu/api/menu/').then(function(response) {
                    let data = JSON.parse(response.data);
                    self.data_menu = data;
                    self.totalRows = data.length;
                });
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