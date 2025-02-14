<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Latihan Soal</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Latihan Soal</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Latihan Soal</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="text-start">
                            <h5 class="card-title">{{ courseDetail.title }}</h5>
                        </div>
                        <div class="ms-auto">
                            <Link class="btn btn-primary btn-sm" :href="`/admin/courses/${course.id}/course-details`">Kembali</Link>
                            <Link class="btn btn-primary btn-sm mx-1" :href="`/admin/courses/${course.id}/course-details/${courseDetail.id}/edit`">Edit Detail Course</Link>
                            <a class="btn btn-danger btn-sm" href="#" @click.prevent="destroy(courseDetail.id)">Hapus</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="video-wrapper mb-5" v-if="courseDetail.link">
                        <iframe 
                            id="video-frame"
                            :src="convertToEmbed(courseDetail.link)" 
                            title="Video player" 
                            frameborder="0" 
                            allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen
                        ></iframe>
                        <div class="overlay" @contextmenu.prevent></div>
                    </div>
                    <div class="ck-content" v-html="courseDetail.description"></div>
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

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Pagination
        },

        // props
        props: {
            course: Object,
            courseDetail: Object,
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

            const destroy = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/courses/${props.course.id}/course-details/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Detail Course Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                destroy,
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
