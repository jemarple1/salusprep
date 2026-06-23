@extends('layouts.app')

@section('meta_title', 'About SalusPrep — NREMT® & NCLEX-PN™ Exam Prep')
@section('meta_description', 'SalusPrep was built by nursing and EMT students to deliver adaptive NREMT® and NCLEX-PN™ practice tests, flashcards, and skill exercises that target your knowledge gaps.')

@section('content')
    <article class="mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold text-white">About &amp; Contact</h1>

        <div class="prose prose-invert mt-8 max-w-none space-y-6 text-slate-300 prose-headings:text-white prose-a:text-medic-light">
            <p class="text-lg leading-relaxed text-slate-200">
                SalusPrep was founded by nursing and EMT students who wanted a more effective way to prepare for the NREMT® and NCLEX-PN® exams.
            </p>

            <p class="leading-relaxed">
                While studying for our own certification exams, we tried a mix of question banks, flashcards, and study apps — and found that most tools were either too generic or focused on memorization rather than identifying knowledge gaps. SalusPrep was built to give students like us adaptive practice quizzes, detailed explanations, and progress tracking that help focus study time where it matters most.
            </p>

            <p class="leading-relaxed">
                Based in Amherst, Massachusetts, we continue to refine SalusPrep with a simple goal: practical, affordable study tools built by students, for EMS and nursing students preparing for certification exams.
            </p>
        </div>

        <div class="mt-10 space-y-4 border-t border-white/10 pt-8">
            <p class="text-sm leading-relaxed text-slate-400">
                SalusPrep is an independent exam preparation platform and is not affiliated with, endorsed by, or sponsored by NREMT® or NCSBN.
            </p>
            <p class="text-sm text-slate-500">Built in Amherst, Massachusetts.</p>

            <a href="mailto:salusprep@mail.com" class="inline-flex rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">
                Email Jeremy
            </a>
        </div>
    </article>
@endsection
