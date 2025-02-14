<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Riwayat Tryout</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="d-flex justify-content-between">
                <div class="ms-start">
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">Riwayat Tryout</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Kunci jawaban & Pembahasan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="ms-auto mb-3">
                    <Link v-if="exam.exam_group_id" :href="`/admin/exam-groups/${exam.exam_group_id}/exam-group-details/${exam.id}`" class="btn btn-primary btn-sm">Kembali</Link>
                    <Link v-else :href="`/admin/exams/${exam.id}`" class="btn btn-primary btn-sm">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <div class="d-flex justify-content-between">
                                <div class="text-start">
                                    <h5 class="mb-0 text-white">No. {{ questionLists[indexPage]['navigation_order'] }} - {{ exam.lesson.name }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-if="question">
                                <div v-html="question['question']" class="prevent-select clearfix ck-content"></div>
                                <ol v-if="exam.type_option == 1 || (examGroup && examGroup.type_option == 1)" type="A">
                                    <div v-for="(answerOrder, index) in questionLists[indexPage]['answer_order'].split(',')" :key="index">
                                        <div v-if="exam.question_title.assessment_type == 4">
                                            <li v-html="'<span class=\'badge bg-danger mb-1\'>poin ' + question['point_' + answerOrder] + '</span>' + question['option_' + answerOrder]" class="mb-3"></li>
                                        </div>
                                        <div v-else>
                                            <li
                                                :class="{
                                                    'ck-content': true,
                                                    'text-success fw-bold fs-6': questionLists[indexPage]['answer'].includes(Number(answerOrder)) || question.answer.includes(Number(answerOrder)), // Kunci jawaban selalu hijau
                                                    'text-danger fw-bold fs-6': !question.answer.includes(Number(answerOrder)) && questionLists[indexPage]['answer'].includes(Number(answerOrder)) // Jawaban yang dipilih salah menjadi merah
                                                }"
                                                v-html="question['option_' + answerOrder]"
                                            ></li>
                                        </div>
                                    </div>
                                </ol>
                            </div>
                            <div v-else>
                                <div class="alert alert-danger border-0 shadow">
                                    <i class="fa fa-exclamation-triangle"></i> Question Not Found!.
                                </div>
                            </div>
                            <hr style="border:1px solid red;">
                            <table v-if="question">
                                <tr>
                                    <th>Jawaban Anda </th>
                                    <th>:</th>
                                    <th>{{ questionLists[indexPage]['answer'].length > 0 ? convertAnswersToAlphabets(questionLists[indexPage]['answer_order'].split(','), questionLists[indexPage]['answer']) : 'Tidak Menjawab' }}</th>
                                </tr>
                                <tr v-if="exam.question_title.assessment_type != 4">
                                    <th>Kunci Jawaban</th>
                                    <th>:</th>
                                    <th>{{ convertAnswersToAlphabets(questionLists[indexPage]['answer_order'].split(','), question.answer) }}</th>
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer" style="min-height: 60px;">
                            <div class="text-center">
                                <button v-if="indexPage > 0" @click="prevPage()" type="button" class="btn btn-primary btn-sm me-3">Sebelumnya</button>
                                <button v-if="indexPage < Object.keys(questionLists).length - 1" @click="nextPage()" type="button" class="btn btn-success btn-sm">Selanjutnya</button>
                            </div>
                        </div>
                    </div>

                    <div class="card border-top border-0 border-3 border-primary">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div class="text-start">
                                    <h5 class="mb-0">Pembahasan</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-html="question['discussion'] ?? 'Tidak Ada Pembahasan'" class="prevent-select ck-content"></div>
                        </div>
                        <div class="card-footer" v-if="question.discussion_video">
                            <div class="text-center">
                                <button  type="button"  class="btn btn-sm btn-danger"  data-bs-toggle="modal"  data-bs-target="#exampleFullScreenModal" @click="openVideo(question.discussion_video)">Pembahasan Video</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 col-lg-4">
                    <div class="card">
                        <div class="card-header text-white bg-primary">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0 text-white">Navigasi Soal</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body mb-3" style="height: 480px; overflow-y: auto">
                            <div v-for="(question, index) in questionLists" :key="index">
                                <div style="width: 20%; float: left;">
                                    <div style="padding: 3px;">
                                        <div v-if="exam.question_title.assessment_type == 4">
                                            <button @click="clickQuestion(index)" v-if="index == indexPage" class="btn btn-primary w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                            <button @click="clickQuestion(index)" v-if="index != indexPage && question.answer != 0" class="btn btn-success w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                            <button @click="clickQuestion(index)" v-if="index != indexPage && question.answer == 0" class="btn btn-secondary w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                        </div>
                                        <div v-else>
                                            <button @click="clickQuestion(index)" v-if="index == indexPage" class="btn btn-primary w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                            <button @click="clickQuestion(index)" v-if="index != indexPage && question.is_correct == 'Y' && question.answer != 0" class="btn btn-success w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                            <button @click="clickQuestion(index)" v-if="index != indexPage && question.is_correct =='N' && question.answer != 0" class="btn btn-danger w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                            <button @click="clickQuestion(index)" v-if="index != indexPage && question.is_correct && question.answer == 0" class="btn btn-secondary w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="row">
                                <div class="col-md-6 sm-12">
                                    <button type="button" class="w-100 mt-2 btn-sm btn btn-primary">Soal Saat Ini</button>
                                </div>
                                <div class="col-md-6 sm-12">
                                    <button type="button" class="w-100 mt-2 btn-sm btn btn-success">{{ exam.question_title.assessment_type == 4 ? 'Dijawab' : 'Jawaban Benar' }}</button>
                                </div>
                                <div v-if="exam.question_title.assessment_type != 4" class="col-md-6 sm-12">
                                    <button type="button" class="w-100 mt-2 btn-sm btn btn-danger">Jawaban Salah</button>
                                </div>
                                <div class="col-md-6 sm-12">
                                    <button type="button" class="w-100 mt-2 btn-sm btn btn-secondary">Tidak Dijawab</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!--end page wrapper -->

    <div class="modal fade" id="exampleFullScreenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Video Pembahasan</h5>
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
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    import MathJax, { initMathJax, renderByMathjax } from 'mathjax-vue3';
    
    import { ref } from 'vue';

    import { Inertia } from '@inertiajs/inertia';

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
            MathJax
        },

        // props
        props: {
            grade: Object,
            examGroup: Object,
            questionLists: Object,
            question: Object,
            exam: Object,
            indexPage: Object,
            valueCategoryId: Object,
            isCorrect: Object,
        },
        mounted() {
            let recaptchaScript = document.createElement('script')
            recaptchaScript.setAttribute('src', '/assets/js/MathJax.js?config=TeX-AMS-MML_HTMLorMML')
            document.head.appendChild(recaptchaScript)

            function onMathJaxReady() {
                const el = document.getElementById('elementId')
                renderByMathjax(el)
            }
            initMathJax({}, onMathJaxReady);
        },

        setup(props) {
            const videoUrl = ref(''); // Menggunakan ref untuk menyimpan URL video

            var indexPage;

            indexPage = ref(props.indexPage);

            let options = ["A", "B", "C", "D", "E"];

            const convertAnswersToAlphabets = ((answerOrder, answers) => {
                const alphabetMap = {};
                const alphabet = ['A', 'B', 'C', 'D', 'E'];

                answerOrder.forEach((num, index) => {
                    alphabetMap[num] = alphabet[index];
                });

                const convertedAnswers = answers.map(answer => alphabetMap[answer]);

                return convertedAnswers.join(', ');
            });

            const prevPage = (() => {
                indexPage.value = parseInt(indexPage.value) - 1;
                Inertia.get(`/admin/exams/${props.exam.id}/grades/${props.grade.id}/answer-corrections`, {indexPage: indexPage.value, valueCategoryId: props.valueCategoryId, isCorrect: props.isCorrect})
                loadMath();
            });

            const nextPage = (() => {
                indexPage.value = parseInt(indexPage.value) + 1;
                Inertia.get(`/admin/exams/${props.exam.id}/grades/${props.grade.id}/answer-corrections`, {indexPage: indexPage.value, valueCategoryId: props.valueCategoryId, isCorrect: props.isCorrect})
                loadMath();
            });

            const clickQuestion = ((index) => {
                indexPage.value = index;
                Inertia.get(`/admin/exams/${props.exam.id}/grades/${props.grade.id}/answer-corrections`, {indexPage: indexPage.value, valueCategoryId: props.valueCategoryId, isCorrect: props.isCorrect})
                loadMath();
            });

            const loadMath = (() => {
                let recaptchaScript = document.createElement('script')
                recaptchaScript.setAttribute('src', '/assets/js/MathJax.js?config=TeX-AMS-MML_HTMLorMML')
                document.head.appendChild(recaptchaScript)

                function onMathJaxReady() {
                    const el = document.getElementById('elementId')
                    renderByMathjax(el)
                }
                initMathJax({}, onMathJaxReady);
            });

            const indexAnswer = ((answerOrder, answer) => {
                var answerArray = answerOrder.split(',').map(Number);
                return options[answerArray.indexOf(answer)];
            });

            const openVideo = (url) => {
                videoUrl.value = convertToEmbed(url); // Ubah URL video menjadi embed URL
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
                convertAnswersToAlphabets,
                indexPage,
                options,
                indexAnswer,

                prevPage,
                nextPage,
                clickQuestion,
                loadMath,
                openVideo,
                stopVideo,
                convertToEmbed,
                videoUrl
            }

        }
    }
</script>
