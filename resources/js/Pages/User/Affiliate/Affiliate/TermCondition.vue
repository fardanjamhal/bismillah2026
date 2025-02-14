<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Program Afiliasi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Program Afiliasi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Syarat & Ketentuan</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <div class="card border-top border-0 border-3 border-primary">
                        <div class="card-header bg-primary">
                            <h5 class="text-white mb-0">
                                Program Afiliasi
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/balances" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-money font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Komisi</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/terms-conditions" class="nav-link active">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-info-circle font-18 me-1"></i></div>
                                            <div class="tab-title m-1">S & K</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/links" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-link font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Link</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/user/affiliates/withdrawals" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-credit-card-front font-18 me-1"></i></div>
                                            <div class="tab-title m-1">Penarikan</div>
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade active show" role="tabpanel">
                                    <div class="ck-content" v-html="setting.terms_and_condition"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->

</template>

<script>
    //import layout
    import LayoutUser from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

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

        //props
        props: {
            setting: Object,
        },
    }
</script>

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>