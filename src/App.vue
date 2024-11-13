<script setup>
    import Header from './components/Header.vue';
    import Input from './components/Input.vue';
    import Home from './components/Home.vue'
    import { ref } from 'vue';

    const bookData = ref({
        title: 'Title',
        author: 'Author',
        cover: '/book_cover.jpg',
        description: 'Description',
        rating: 0
    });

    function handleBook(data) {
        bookData.value = data;
    }
</script>

<template>
    <Header></Header>
    <Input @updateBook="handleBook"></Input>
    <Home :title="bookData.title" :author="bookData.author" :cover="bookData.cover" :description="bookData.description" :rating="bookData.rating"></Home>
</template>