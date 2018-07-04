<template>
    <div>
        <div class="row">
            <div class="col" v-for="article_data in article_data">
                <article class="article">
                    <a class="article__a" v-bind:href="'/article/'+article_data.id+'/'+article_data.title"><header class="article__header">
                        <img v-bind:src="'/'+article_data.image" alt="takka" class="article__header__img">
                        <h2 class="article__header__h2">{{ article_data.title }}</h2>
                    </header>
                    <section class="article__section--description">
                        {{ article_data.description }}
                    </section></a>
                    <footer class="article_footer">
                        <div class="article__footer__div">
                            <div class="article__footer__div__div">
                                <span class="article__footer__div__div__span material-icons" aria-hidden="true">date_range</span>
                                <time v-bind:datetime="article_data.date | moment('DD-MM-YYYY HH:mm')">{{ article_data.date | moment("DD.MM.YYYY HH:mm") }}</time>
                            </div>
                            <div class="article__footer__div__div">
                                <span class="article__footer__div__div__span material-icons" aria-hidden="true">comment</span>
                                {{ article_data.comment_length }}
                            </div>
                        </div>
                    </footer>
                </article>
            </div>
        </div>
        <div class="div--navigate d-flex justify-content-center">
            <a v-if="previous_page.status" class="btn btn-primary div--navigate__a" v-bind:href="previous_page.url"  role="button">Poprzednia strona</a>
            <a v-if="next_page.status" class="btn btn-primary div--navigate__a" v-bind:href="next_page.url" role="button">Następna strona</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['article_data', 'page', 'size'],
        data () {
            return {
                next_page: {
                    'url': '',
                    'status': ''
                },
                previous_page: {
                    'url': '',
                    'status': ''
                },
            }
        },
        mounted: function () {
            let page = parseInt(this.page) + 1;
            if(page*6 < this.size)
            {
                this.next_page.status = true;
                this.next_page.url = '/home/' + page;
            }
            if(page !== 1)
            {
                page -= 2;
                this.previous_page.status = true;
                this.previous_page.url = '/home/' + page;
            }
        }
    }
</script>

<style scoped lang="scss">
    .article
    {
        margin-bottom: 30px;
        max-width: 350px;
    }
    .article__header__img
    {
        width: 350px;
        height: 265px;
    }
    .article__header__h2
    {
        margin-top: 15px;
        font-size: 22px;
        font-weight: bold;
        color: #484848;
    }
    .article__section--description
    {
        font-size: 14px;
        text-align: justify;
        color: #8a8a8a;
        padding-bottom: 6px;
        border-bottom: 3px solid #c5c5c5;
    }
    .article__footer__div
    {
        display: flex;
        justify-content: space-between;
    }
    .article__footer__div__div
    {
        color: #999999;
        font-size: 13px;
        margin-top: 10px;
    }
    .article__footer__div__div__span
    {
        font-size: 13px;
    }
    .article__a
    {
        &:hover{
            text-decoration: underline black;
        }
    }
    .div--navigate__a
    {
        margin-left: 10px;
    }
    @media (max-width: 990px) {
        .article
        {
            margin: 0 auto 30px;
        }
    }
    @media (max-width: 425px) {
        .article__header__img
        {
            width: 250px;
        }
    }
</style>