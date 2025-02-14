<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - 403 Forbidden</title>
    </Head>
    <!--start page wrapper -->
        <div class="d-flex align-items-center justify-content-center" style="margin-top:100px;">
			<div class="container">
				<div class="card py-5">
					<div class="row g-0">
						<div class="col col-xl-12">
							<div class="card-body p-4 text-center">
								<h1 class="display-1"><span class="text-primary">4</span><span class="text-danger">0</span><span class="text-success">3</span></h1>
								<h2 class="font-weight-bold display-4">Forbidden</h2>
								<p>You have reached the edge of the universe. The page you requested could not be found. Dont'worry and return to the previous page.</p>
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
    // import LayoutAdmin from '../../Layouts/Layout.vue';


    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';


    export default {
        // layout
        // layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },
    }
</script>
