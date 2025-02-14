<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Course</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Course</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Course</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr/>
            <div v-if="!courses.data.length">
                <div class="row row-cols-12 row-cols-md-12 row-cols-lg-12 row-cols-xl-12">
                    <div class="col">
                        <div class="card border-bottom border-3 border-0">
                            <div class="card-body">
                                <h5 class="card-title text-center">Course Belum Tersedia</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-3 rounded-3">
                    <div class="input-group flex-md-nowrap flex-wrap">
                        <input type="text" class="form-control form-control-sm sm-2" placeholder="Judul Course ....">

                        <select class="form-control form-control-sm sm-2">
                            <option value="">[ Kategori ]</option>
                            <option v-for="(category, index) in categories" :key="index" :value="category.id">
                                {{ category.name }}</option>
                        </select>

                        <button class="btn btn-primary btn-sm"><i class="bx bx-filter"></i></button>
                        <Link class="btn btn-danger btn-sm"><i class="bx bx-refresh"></i></Link>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" v-for="course in courses.data" :key="course.id">
                    <div class="card border-0 position-relative">
                        <img v-bind:src="'/storage/upload_files/courses/' + course.thumbnail" class="card-img" />

                        <div class="card-header" style="min-height: 72px;">
                            <h6 class="card-title">{{ course.title }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <template v-if="course.member_categories && course.member_categories.length">
                                    <span v-for="(memberCategory, subIndex) in course.member_categories" :key="subIndex" class="badge bg-success me-1">
                                        {{ memberCategory.name }}
                                    </span>
                                </template>
                                <span v-else class="badge bg-success">
                                    Seluruh Member & Non Member
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="w-25 p-1 rounded bg-danger text-center text-white text-sm font-semibold">
                                    Disc 60%
                                </div>
                                <div class="text-end">
                                    <span class="line-through text-danger font-bold text-sm d-block">
                                        Rp 250.000
                                    </span>
                                    <span class="text-slate-600 font-bold text-base">
                                        Rp 100.000
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a :href="`/user/courses/${course.id}`" class="btn btn-primary btn-sm">Lihat Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="courses.data.length">
                <div class="col mb-4">
                    <Pagination :links="courses.links" align="center" />
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutUser from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';
    
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
            Pagination
        },

        // props
        props: {
            courses: Object,
            categories: Object,
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed().replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        }
    }
</script>

<style>
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }

    .label-free {
        animation: blink 1.5s infinite;
    }
</style>
