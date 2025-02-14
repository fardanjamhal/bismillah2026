<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Transaksi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Transaksi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Transaksi Berhasil</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card py-3">
					<div class="row">
                        <div class="col-xl-6">
							<img src="/assets/images/payment_success.gif" class="img-fluid" alt="" width="100%">
						</div>
						<div class="col col-xl-6">
							<div class="card-body p-4">
								<h1 class="display-2"><span class="text-primary">Se</span><span class="text-danger">la</span><span class="text-success">mat</span></h1>
								<h2 class="font-weight-bold display-6">Pembayaran berhasil</h2>
								<p>Terimakasih sudah menggunakan layanan kami.
                                <br>Jika anda kendala atau permasalahan selama penggunaan aplikasi
                                <br>silakan hubungi admin untuk konsultasi.</p>
								    <div class="mt-5"> 
                                    <a href="/user/dashboard" class="btn btn-primary px-md-5 radius-30">Dashboard</a>
                                    <a href="/user/transactions" class="btn btn-outline-primary ms-3 px-md-5 radius-30">Riwayat Transaksi</a>
                                </div>
							</div>
						</div>
					</div>
					<!--end row-->
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
    }
</script>
