<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Video Pembelajaran</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Video Pembelajaran</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Video Pembelajaran</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr/>
            <div v-if="!categoryVideoModules.length">
                <div class="row row-cols-12 row-cols-md-12 row-cols-lg-12 row-cols-xl-12">
                    <div class="col">
                        <div class="card border-bottom border-3 border-0">
                            <div class="card-body">
                                <h5 class="card-title text-center">Data Video Pembelajaran Belum Tersedia.</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-for="category in categoryVideoModules" :key="category.id">
                <h5 class="mb-3">Video Pembelajaran {{ category.name }}</h5>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" v-for="videoModule in category.video_module" :key="videoModule.id">
                        <div class="card">
                            <span class="badge bg-danger" style="position:absolute; right:-15px; top:-15px; width:50px; height: 35px; font-size: 20px;">
                                <i class="fadeIn animated bx bx-book"></i>
                            </span>
            
                            <div class="card-body">
                                <div class="card-title mt-1" style="min-height: 40px;">
                                    <h6 class="mb-0">{{ videoModule.title }}</h6>
                                </div>
                                <hr/>
                                <p class="card-text" style="min-height: 60px;">{{ videoModule.description }}</p>
                                <div v-if="$page.props.auth.user.member_type == 2" class="text-center">
                                    <div v-if="videoModule.member_categories.length > 0">
                                        <div v-for="(member_category, index) in videoModule.member_categories" :key="index" style="display: inline;">
                                            <span class="badge bg-success ms-1">{{ member_category.name }}</span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <span class="badge bg-success ms-1">Seluruh Member & Non Member</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <div v-if="checkMemberCategories(videoModule.member_categories) || $page.props.auth.user.member_type == 1">
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-primary w-100" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#exampleFullScreenModal"
                                            @click="openVideo(videoModule.link, videoModule.title)"
                                        >
                                            Buka Video
                                        </button>
                                    </div>
                                    <div v-else>
                                        <Link :href="`/user/vouchers?category_id=${videoModule.category_id}`" class="btn btn-outline-primary btn-sm w-100">
                                            <span v-if="videoModule.member_categories.length == 1">Upgrade Ke {{ videoModule.member_categories[0] ? videoModule.member_categories[0].name : '' }}</span>
                                            <span v-else>Upgrade Member</span>
                                        </Link> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->

    <div class="modal fade" id="exampleFullScreenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ modalTitle }}</h5> <!-- Menggunakan modalTitle untuk judul -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="stopVideo()"></button>
                </div>
                <div class="modal-body p-0 m-0">
                    <iframe 
                        width="100%" 
                        id="video-frame" 
                        height="100%" 
                        :src="videoUrl" 
                        title="Video player" 
                        frameborder="0" 
                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen
                    ></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="stopVideo()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout admin
    import LayoutUser from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    import { ref } from 'vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';


    export default {
        // layout
        layout: LayoutUser,

        // register components
        components: {
            Link,
            Head,
        },

        // props
        props: {
            categoryVideoModules: Object,
            userMemberCategories: Object
        },
        setup(props) {
            const videoUrl = ref(''); // Menggunakan ref untuk menyimpan URL video
            const modalTitle = ref('Pembahasan Soal Video'); // Judul modal default

            const checkMemberCategories = (categories) => {
                if (categories.length > 0) {
                    const categoryIds = categories.map(category => category.id);
                    return props.userMemberCategories.some(entry => categoryIds.includes(entry.member_category_id));
                } else {
                    return true;
                }
            };

            const openVideo = (url, title) => {
                videoUrl.value = convertToEmbed(url); // Ubah URL video menjadi embed URL
                modalTitle.value = title; // Setel judul modal sesuai dengan video
            };

            const stopVideo = () => {
                videoUrl.value = ''; // Setel URL menjadi kosong untuk menghentikan video
            };

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
                checkMemberCategories,
                openVideo,
                stopVideo,
                convertToEmbed,
                videoUrl,
                modalTitle
            }
        }
    }
</script>
