@extends('layout')

@section('head')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
@endsection

@section('main')

<main class="container" style="background-color: #fff;">
    <section id="contact-us">
        <h1 style="padding-top: 50px;">Create New Post!</h1>

        <!-- Contact Form -->
        <div class="contact-form" style="padding-left:40px; padding-right:40px; padding-bottom:20px;">
            <form action="" method="">
                <!-- Title -->
                <label for="title"><span>Title</span></label>
                <input type="text" id="title" name="title" />

                <!-- Image -->
                <label for="image"><span>Image</span></label>
                <input type="file" id="image" name="image" />

                <!-- Body-->
                <label for="body"><span>Body</span></label>
                <textarea id="body" name="body"></textarea>

                <!-- Button -->
                <input type="submit" value="Submit" />
            </form>
        </div>

    </section>
</main>

@endsection


@section('scripts')
    <script>
        CKEDITOR.replace( 'body' );
    </script>
@endsection
