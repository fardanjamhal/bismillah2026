<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Detail Video Pembelajaran</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Detail Video Pembelajaran</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ detailVideoModule.title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <h5 class="card-title">{{ detailVideoModule.title }}</h5>
                        </div>
                        <div class="ms-auto">
                            <Link class="btn btn-primary btn-sm" :href="`/user/video-modules/${videoModule.id}`">Kembali</Link>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="video-wrapper mb-5" v-if="detailVideoModule.link">
                        <iframe 
                            id="video-frame"
                            :src="convertToEmbed(detailVideoModule.link)" 
                            title="Video player" 
                            frameborder="0" 
                            allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen
                        ></iframe>
                        <div class="overlay" @contextmenu.prevent></div>
                    </div>
                    <div class="ck-content" v-html="detailVideoModule.description"></div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        // props
        props: {
            videoModule: Object,
            detailVideoModule: Object,
        },

        setup(props) {

            const convertToEmbed = (url) => {
                if (url.includes("drive.google.com")) {
                    const fileId = url.match(/\/d\/(.*?)\//)[1];
                    return `https://drive.google.com/file/d/${fileId}/preview`;
                } else if (url.includes("youtube.com")) {
                    const videoId = url.split('v=')[1];
                    return `https://www.youtube.com/embed/${videoId}`;
                } else if (url.includes("youtu.be")) {
                    const videoId = url.split('.be/')[1];
                    return `https://www.youtube.com/embed/${videoId}`;
                }
                return url;
            };

            return {
                convertToEmbed
            }
        }
    }
</script>

<style>
    .video-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
    }

    #video-frame {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
