<script setup>
import { ref } from 'vue';
import { RouterLink } from 'vue-router';

const search = ref("");
const isSearchNotOk = ref(false);
const bookInfo = ref({});
const bookID = ref("");

async function sendBook(){
    try {
        const response = await fetch(`http://localhost/book_realm/backend/index.php?book=${encodeURIComponent(search.value)}`);

        if(!response.ok){
            console.log('Could not fetch resources');
            isSearchNotOk.value = true; 
            return;
        }

        getBooks();
    } catch (error) {
        console.error('Fetch error:', error);
        isSearchNotOk.value = true;
    }
}

async function getBooks(){
    bookInfo.value = {};
    
    try {
        const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=${search.value}`);

        if(!response.ok){
            isSearchNotOk.value = true;
            throw new Error("Could not fetch resources");
        }

        const data = await response.json();

        bookInfo.value = data.items[0].volumeInfo;
        bookID.value = data.items[0].id;
        isSearchNotOk.value = false;
    } catch (error) {
        console.error(error);
        isSearchNotOk.value = true;
    }
}
</script>

<template>
    <main>
        <input type="text" v-model="search" @keyup.enter="sendBook" placeholder="Search a book...">

        <RouterLink :to="`/book/${bookID}`">
            <div class="book" v-if="bookInfo.title">
                <img :src="bookInfo.imageLinks?.smallThumbnail" :alt="bookInfo.title">

                <h1>{{ bookInfo.title }}</h1>

                <h2>{{ bookInfo.authors[0] }}</h2>

                <p>Click for more info!</p>
            </div>
        </RouterLink>
        <p v-if="isSearchNotOk">No results</p>
    </main>
</template>

<style scoped>
    main {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4vw;
        padding-top: 1%;
    }

    input {
        width: 20vw;
        height: 7.5vh;
        border-radius: 2vw / 2vw;
        padding-left: 1%;
        font-size: 1.5vw;
    }

    .book {
        width: 26vw;
        height: 60vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1vw;
        background-color: #f7e5f6;
        padding-top: 3%;
        border-radius: 2vw / 2vw;
        cursor: pointer;
        position: relative;
        border: 4px solid transparent;
        border-radius: 16px;
        background-clip: padding-box;
        box-shadow: 0 3px 9px black, inset 0 0 9px white;
    }

    .book::after {     
        position: absolute;
        top: -4px; bottom: -4px;
        left: -4px; right: -4px;
        background: linear-gradient(red, blue);
        content: '';
        z-index: -1;
        border-radius: 16px;
    }

    .book img {
        width: 12vw;
    }
</style>
