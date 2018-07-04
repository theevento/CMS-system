<template>
    <div class="row">
        <div class="col div--article">
            <article class="article">
                <header class="article__header">
                    <div class="article__header__div article__header__div--time">
                        <time class="article__header__div__time" v-bind:datetime="article_data.date" v-html="date"></time>
                    </div>
                    <div class="article__header__div">
                        <h2 class="article__header__div__h2">{{ article_data.title }}</h2>
                        <div class="article__header__div__div">
                            <span class="article__header__div__div__span">Opublikowane przez {{ author.name }} w</span>
                            <span class="article__header__div__div__span--tags">
                                <a href="#" v-for="tags in tags" class="article__header__div__div__span--tags__a">{{ tags.tag }}</a>
                            </span>
                            <a href="#comments" class="article__header__div__div__a">{{ comments_length }} komentarzy</a>
                        </div>
                    </div>
                </header>
                <article class="article__article">
                    <img v-bind:src="'/' + article_data.image" alt="" class="article__article__img">
                    <div class="article__article__div" v-html="article_data.article"></div>
                </article>
            </article>
            <div class="div">
                <div class="div__div--comments" id="comments">
                    <p class="div__div--comments__p"> {{ comments_length }} komentarzy</p>
                    <div class="div__div--comments__div" v-for="comments in comments">
                        <img class="div__div--comments__div__img" src="http://s3.amazonaws.com/37assets/svn/765-default-avatar.png" alt="">
                        <div class="div__div--comments__div__div">
                            <p class="div__div--comments__div__div__p">{{ comments.name }}</p>
                            <div class="div__div--comments__div__div__div"> {{ comments.comment }} </div>
                        </div>
                    </div>
                </div>
                <div class="div__div--addcomment" id="add_comment">
                    <p class="div__div--addcomment__p">Dodaj komentarz</p>
                    <span class="div__div--addcomment__span">Twój adres e-mail nie zostanie opublikowany</span>
                    <div class="div__div--addcomment__div" v-html="form">
                    </div>
                </div>
            </div>
            </div>
            <aside class="col-3 aside">
                <p class="aside__p">Popularne artykuły</p>
                <article v-for="popular in popular" class="aside__article">
                    <a class="aside__article" v-bind:href="'/article/' + popular.id + '/' + popular.title">
                        <div class="aside__article__a__div">
                            <img class="aside__article__a__div__img" alt="" v-bind:src="'/' + popular.image">
                        </div>
                        <div class="aside__article__a__div">
                            <h2 class="aside__article__a__div__h2">{{ popular.title }}</h2>
                            <time class="aside__article__a__div__time" v-bind:datetime="popular.date">{{ popular.date | moment("DD.MM.YYYY") }}</time>
                        </div></a>
                </article>
            </aside>
        </div>
</template>

<script>
    export default {
        props: ['form', 'article_data', 'tags', 'comments', 'author', 'popular',],
        data () {
            return {
                monthNames: ["STY", "LUT", "MAR", "KWI", "MAJ", "CZE",
                    "LIP", "SIE", "WRZ", "PAŹ", "LIS", "GRU"
                ],
                date: '',
                comments_length: this.comments.length
            }
        },
        mounted: function () {
            let date = new Date(this.article_data.date.timestamp*1000);
            this.date = date.getUTCDate() + '<br>' + this.monthNames[date.getMonth()];
        }
    }
</script>

<style scoped lang="scss">
    .article
    {
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 4px solid #c8c8c8;
        width: 100%;
    }
    .article__header
    {
        display: flex;
        align-items: center;
    }
    .article__header__div
    {
        width: 100%;
    }
    .article__header__div--time
    {
        font-size: 22px;
        background: #ececec;
        color: #555555;
        padding: 20px;
        border-radius: 50%;
        margin-right: 10px;
        width: 100px;
        height: 100px;
        font-weight: bold;
        text-align: center;
    }
    .article__header__div__h2
    {
        color: #555555;
        font-size: 25px;
        padding-bottom: 7px;
        margin-bottom: 7px;
        border-bottom: 4px solid #c8c8c8;
        font-weight: bold;
    }
    .article__header__div__div__span, .article__header__div__div__span--tags
    {
        color: #9f9f9f;
    }
    .article__header__div__div__a
    {
        color: #6b99b6;
    }
    .article__header__div__div__span--tags__a
    {
        color: #6b99b6;
        &::after{
            content: ', ';
        }
        &:last-child::after{
            content: '';
        }
        &:last-child{
            margin-right: 3px;
            padding-right: 6px;
            border-right: 2px solid #cbcbcb;
        }
    }
    .article__article
    {
        margin-top: 30px;

    }
    .article__article__img
    {
        width: 100%;
        height: 500px;
    }
    .article__article__div
    {
        margin-top: 50px;
        text-align: justify;
    }
    .div__div--related__p
    {
        color: #555555;
        font-weight: bold;
        font-size: 20px;
    }
    .div__div--related__div
    {
        display: flex;
        padding-bottom: 40px;
        margin-bottom: 40px;
        border-bottom: 4px solid #c8c8c8;
    }
    .div__div--related__div__a
    {
        display: block;
        margin-right: 10px;
        &:last-child
        {
            margin-right: 0;
        }
    }
    .div__div--related__div__img
    {
        width: 100%;
    }
    .div__div--comments
    {
        padding-bottom: 30px;
        margin-bottom: 30px;
        border-bottom: 4px solid #c8c8c8;
    }
    .div__div--comments__p
    {
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 40px;
    }
    .div__div--comments__div
    {
        display: flex;
        margin-bottom: 30px;
    }
    .div__div--comments__div__img
    {
        width: 100px;
        height: 100px;
        margin-right: 30px;
    }
    .div__div--comments__div__div__p
    {
        font-weight: bold;
        padding-bottom: 5px;
        border-bottom: 1px solid #e1e1e1;
    }
    .div__div--comments__div__div
    {
        padding: 20px;
        border: 1px solid #e1e1e1;
        width: 100%;
    }
    .div__div--addcomment__p
    {
        font-weight: bold;
        font-size: 17px;
        margin: 0;
        padding: 0;
    }
    .div__div--addcomment__span
    {
        font-size: 12px;
    }
    .div__div--addcomment__div
    {
        margin-top: 40px;
    }
    .aside
    {
        margin-left: 30px;
        padding-left: 30px;
        border-left: 4px solid #c8c8c8;
    }
    .aside__p
    {
        font-weight: bold;
    }
    .aside
    {
        height: 68vh;
    }
    .aside__article
    {
        display: flex;
        margin-bottom: 10px;
    }
    .aside__article--last
    {
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    .aside__article__a__div__img
    {
        width: 100px;
        height: 100px;
        margin-right: 10px;
    }
    .aside__article__a__div__h2
    {
        font-size: 18px;
        padding: 0;
        margin: 0;
        color: #555555;
    }
    .aside__article__a__div__time
    {
        font-size: 12px;
        color: #acacac;
    }
</style>