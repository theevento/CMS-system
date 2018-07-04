<template>
    <div>
        <b-navbar type="dark" variant="dark" toggleable>
            <b-navbar-toggle target="nav_dropdown_collapse"></b-navbar-toggle>
            <b-collapse is-nav id="nav_dropdown_collapse">
                <b-navbar-nav>
                    <b-nav-item-dropdown text="Artykuły" left>
                        <b-dropdown-item href="/admin/article/add">Dodaj artykuł</b-dropdown-item>
                        <b-dropdown-item href="/admin/article/edit">Edytuj artykuł</b-dropdown-item>
                    </b-nav-item-dropdown>
                    <b-nav-item-dropdown text="Strony" left>
                        <b-dropdown-item href="/admin/page/add">Dodaj strone</b-dropdown-item>
                        <b-dropdown-item href="/admin/page/edit">Edytuj stronę</b-dropdown-item>
                    </b-nav-item-dropdown>
                    <b-nav-item-dropdown text="Menu" left>
                        <b-dropdown-item href="/admin/menu/add">Dodaj zakładkę</b-dropdown-item>
                        <b-dropdown-item href="/admin/menu/edit">Edytuj zakładkę</b-dropdown-item>
                    </b-nav-item-dropdown>
                    <b-nav-item href="/admin/settings">Ustawienia globalne</b-nav-item>
                </b-navbar-nav>
                <b-navbar-nav class="ml-auto">
                    <b-nav-item href="/">Wyjdź</b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
    export default {
    }
</script>

<style scoped>

</style>