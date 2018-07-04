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

        <b-form  @submit="onSubmit">
            <b-form-group label="Tytuł artykułu:" label-for="title">
                <b-form-input id="title"  required v-model="form.title" type="text"  placeholder="Wpisz tytuł.."></b-form-input>
            </b-form-group>
            <b-form-group label="Tagi:" label-for="tags" description="Każdy tag oddzielaj przecinkiem">
                <b-form-input id="tags" required v-model="form.tags" type="text"  placeholder="Wpisz tagi.."></b-form-input>
            </b-form-group>
            <b-form-group label="Opis:" label-for="description" description="Maksymalna długość opisu to 250 znaków">
                <b-textarea id="description" required v-model="form.description" placeholder="Wpisz opis.."></b-textarea>
            </b-form-group>
            <b-form-group label="Zdjęcie artykułu:"  label-for="file">
                <b-form-file accept=".jpg, .png, .jpeg" required v-if="status.uploadReady" id="file" v-model="form.file" placeholder="Wybierz plik.."></b-form-file>
            </b-form-group>
            <b-form-group>
                    <b-form-checkbox v-model="form.active" unchecked-value="false" value="true">Artykuł aktywyny</b-form-checkbox>
            </b-form-group>
            <b-form-group>
                <vue-ckeditor v-model="form.article" :config="config" id="article"></vue-ckeditor>
            </b-form-group>
            <b-button type="submit" variant="primary" class="btn-block">Dodaj artykuł</b-button>
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
                form: {
                    title: '',
                    tags: '',
                    file: null,
                    active: 'false',
                    article: '',
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
          onSubmit: function (event) {
              event.preventDefault();
              this.status.fail = null;
              this.status.success = null;
              let form = new FormData();
              let self = this;
              form.append('file', this.form.file);
              form.append('title', this.form.title);
              form.append('tags', this.form.tags);
              form.append('active', this.form.active);
              form.append('article', this.form.article);
              form.append('description', this.form.description);
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
              this.status.uploadReady = false;
              this.$nextTick(() => {
                  this.status.uploadReady = true;
              });
              this.form.file = null;
              this.form.title = '';
              this.form.tags = '';
              this.form.article = '';
              this.form.active = 'false';
              this.form.description = '';
          }
        },
    }
</script>

<style scoped>
    .div{
        margin-top: 20px;
    }
</style>