<template>
    <div class="main__div">
        <b-row>
            <b-col>
                <b-form-group  label="Wyszukaj artykuł:" class="text-center">
                    <b-input-group>
                        <b-form-input v-model="filter" placeholder="Tytuł artykułu.."></b-form-input>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col>
                <b-form-group label="Ilość artykułów na stronę:" class="text-center">
                    <b-form-select :options="pageOptions" v-model="perPage"></b-form-select>
                </b-form-group>
            </b-col>
        </b-row>
        <b-table :filter="filter" :current-page="currentPage" :per-page="perPage" responsive striped hover :items="data_article" :fields="fields">
            <span slot="active" slot-scope="row">{{row.value?'TAK':'NIE'}}</span>
            <span slot="date" slot-scope="row"> {{ row.value | moment("DD.MM.YYYY HH:mm") }} </span>
            <template slot="actions" slot-scope="row">
                <b-btn v-b-modal.modalInfo size="sm" @click.stop="info(row.item)" class="mr-1">
                    Edytuj
                </b-btn>
            </template>
        </b-table>
        <b-pagination class="justify-content-md-center" :total-rows="totalRows" :per-page="perPage" v-model="currentPage" ></b-pagination>
        <b-modal size="lg" id="modalInfo" :title="'Edycja artykułu'" ok-only>
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
            <b-form  @submit="onSubmit">
            <b-form-group label="Tytuł artykułu:" label-for="title">
                    <b-form-input id="title"  required v-model="article.title" type="text"  placeholder="Wpisz tytuł.."></b-form-input>
                </b-form-group>
                <b-form-group label="Tagi:" label-for="tags" description="Każdy tag oddzielaj przecinkiem">
                    <b-form-input id="tags" required v-model="article.tags" type="text"  placeholder="Wpisz tagi.."></b-form-input>
                </b-form-group>
                <b-form-group label="Opis:" label-for="description" description="Maksymalna długość opisu to 250 znaków">
                    <b-textarea id="description" required v-model="article.description" type="text"  placeholder="Wpisz opis.."></b-textarea>
                </b-form-group>
                <b-form-group label="Zdjęcie artykułu:"  label-for="file">
                    <b-form-file accept=".jpg, .png, .jpeg" v-if="status.uploadReady" id="file" v-model="article.file" placeholder="Wybierz plik.."></b-form-file>
                </b-form-group>
                <b-form-group>
                    <b-form-checkbox v-model="article.active" unchecked-value="false" value="true">Artykuł aktywyny</b-form-checkbox>
                </b-form-group>
                <b-form-group>
                    <vue-ckeditor v-model="article.article" :config="config" id="article"></vue-ckeditor>
                </b-form-group>
                <b-button type="submit" variant="primary" class="btn-block">Aktualizuj artykuł</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        mounted: function () {
            this.getArticle();
        },
        data () {
            return {
                config: {
                    height: 150,
                    extraPlugins: 'codesnippet',
                    codeSnippet_theme: 'pojoaque'
                },
                data_article: [],
                totalRows: 0,
                perPage: 10,
                pageOptions: [ 5, 10, 15 ],
                currentPage: 1,
                filter: '',
                fields: {
                    title: {
                        label: 'Tytuł',
                        'class': 'text-center',
                        sortable: true
                    },
                    date: {
                        label: 'Data',
                        'class': 'text-center',
                        sortable: true
                    },
                    active: {
                        label: 'Aktywny',
                        'class': 'text-center',
                        sortable: true
                    },
                    actions: {
                        label: 'Działania',
                        'class': 'text-center'
                    }
                },
                article: {
                    id: null,
                    article: null,
                    active: null,
                    title: null,
                    tags: null,
                    file: null,
                    description: ''
                },
                status:{
                    fail: null,
                    success: null,
                    message: [],
                    uploadReady: true,
                }
            }
        },
        methods: {
            info (content) {
                let self = this;
                this.article.id = content.id;
                this.article.article = content.article;
                this.article.active = content.active;
                this.article.title = content.title;
                this.article.description = content.description;
                axios.get('/admin/api/tags/' + content.id).then(function(response) {
                        self.article.tags = response.data;
                    });
            },
            onSubmit: function (event) {
                event.preventDefault();
                this.status.fail = null;
                this.status.success = null;
                let form = new FormData();
                let self = this;
                form.append('file', this.article.file);
                form.append('title', this.article.title);
                form.append('tags', this.article.tags);
                form.append('active', this.article.active);
                form.append('article', this.article.article);
                form.append('id', this.article.id);
                form.append('description', this.article.description);

                axios.post(window.location.href, form).then(function(response){
                    console.log(response);
                    if(response.data.status === true)
                    {
                        self.status.success = true;
                        self.status.message = response.data.message;
                        self.onRestart();
                        self.getArticle();
                    }
                    else
                    {
                        self.status.fail = true;
                        self.status.message = response.data.message;
                    }
                });
            },
            onRestart: function () {
                this.status.uploadReady = false;
                this.$nextTick(() => {
                    this.status.uploadReady = true;
                });
            },
            getArticle: function () {
                let self = this;
                axios.get('/admin/api/article/').then(function(response) {
                    let data = JSON.parse(response.data);
                    self.data_article = data;
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