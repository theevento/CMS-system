<template>
    <div>
        <h2 class="h2">Znalezione artykuły:</h2>
        <article class="article" v-for="article_data in article_data">
            <a class="article__a" v-bind:href="'/article/'+article_data.id+'/'+article_data.title">
                <b-row>
                    <b-col cols="2">
                        <img class="article__a__img" v-bind:src="'/'+article_data.image" alt="">
                    </b-col>
                    <b-col cols="8">
                        <div class="article__a__div">
                            <h2>{{ article_data.title }}</h2>
                            <div class="article__a__div__div">
                                {{ article_data.description }}
                            </div>
                        </div>
                    </b-col>
                </b-row>
            </a>
        </article>
    </div>
</template>

<script>
    export default {
        props: ['article_data']
    }
</script>

<style scoped>
    .h2
    {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #a4a6a8;
    }
    .article
    {
        margin-top: 30px;
    }
    .article__a
    {
        color: #000000 !important;
    }
    .article__a__img
    {
        width: 100%;
        height: 200px;
    }
</style>