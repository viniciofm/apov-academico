<template>
    <div class="container-fluid">
        <div class="header">
            <h1 class="header-title">
                {{ headerTitle ? headerTitle : (`Gerenciamento de ${module}`)}}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">In&iacute;cio</a></li>
                    <li class="breadcrumb-item" v-for="link in links">
                        <router-link :to="link[0]">
                            <span class="m-nav__link-text">{{ link[1] }}</span>
                        </router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{title}}</li>
                </ol>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            links: {},
            title: '',
            module: '',
            headerTitle: '',
        }
    }
</script>
