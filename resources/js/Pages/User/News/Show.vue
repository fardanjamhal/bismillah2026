<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Detail Berita</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Berita</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Berita</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="news-title text-center mt-3">{{ news.title }}</h2>

                    <div class="news-meta mb-3 text-center">
                        <span class="text-muted">Dibuat oleh {{ news.user.name }} pada {{ formatDateWithTime(news.created_at) }}</span>
                    </div>
                    
                    <div class="news-thumbnail mb-3">
                        <img v-bind:src="'/storage/upload_files/news/' + news.thumbnail" alt="Thumbnail Berita" class="img-fluid rounded" />
                    </div>

                    <div class="news-description">
                        <div class="ck-content" v-html="news.description"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    // Import layout admin
    import LayoutAdmin from '../../../Layouts/Layout.vue';

    // Import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // Import Head from Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    export default {
        // Layout
        layout: LayoutAdmin,

        // Register components
        components: {
            Link,
            Head,
        },

        // Props
        props: {
            news: Object,
        },
    }
</script>

<style scoped>

.news-thumbnail {
    text-align: center;
}

.news-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}

.news-meta {
    font-size: 0.9rem;
    color: #888;
}

.news-description {
    margin-top: 15px;
    line-height: 1.6;
}

.ck-content {
    /* background-color: #f9f9f9; */
    padding: 15px;
    border-radius: 5px;
}
</style>
