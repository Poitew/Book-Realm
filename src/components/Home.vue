<script setup>
    import { ref } from 'vue';

    // General
    const isSearchOk = ref(true);
    const isLoading = ref(false);
    const bookInfo = ref({});
    const bookID = ref("");

    // Work API - Author API - Cover API -> All related to the random function
    const randomNumber = ref(0);
    const isRandom = ref(false);
    const authorInfo = ref({}); 
    const authorKey = ref();
    const coverID = ref(0);
    const cover = ref("");
    const isAllDone = ref(false)

    // Search API
    const search = ref("");
    const description = ref(""); // Work API is used to get the description since the Search API doesn't provide one
    
    function setSearchType_random(){
        isRandom.value = true;
        isAllDone.value = false
    }

    function setSearchType_search(){
        isRandom.value = false;
        isAllDone.value = false
    }

    async function sendBook(){
        const response = await fetch(`http://localhost/book_realm/backend/index.php?book=${encodeURIComponent(search.value)}`);
        if(!response.ok){
            console.log('Could not fetch resources');
            isSearchOk.value = false; 
            return;
        }
        getBooks();
    }

    async function getBooks(){
        bookInfo.value = {}; // Reset book or else if the search has no result it will show previous result

        try {
            isLoading.value = true;
            let response;
            let data;

            if(isRandom.value){
                // Work API - Generate random number and get the book
                randomNumber.value = Math.floor(Math.random() * (9999999 - 20000 + 1)) + 20000;
                console.log(randomNumber.value)

                response = await fetch(`https://openlibrary.org/works/OL${randomNumber.value}W.json`)
                if(!response.ok){
                    throw new Error("Could not fetch resources, Work API");
                }

                data = await response.json();

                bookInfo.value = data;
                bookID.value = bookInfo.value.key;
                coverID.value = bookInfo.value.covers;
                authorKey.value = bookInfo.value.authors[0].author.key || 'Unknown';

                // Author API
                response = await fetch(`https://openlibrary.org${authorKey.value}.json`)
                if(!response.ok){
                    throw new Error("Could not fetch resources, Author API");
                }

                data = await response.json();

                authorInfo.value = data;

                // Cover API
                // Check if there is a cover
                if (bookInfo.value.covers && bookInfo.value.covers.length > 0) {
                    coverID.value = bookInfo.value.covers[0];
                    cover.value = `https://covers.openlibrary.org/b/id/${coverID.value}-M.jpg`;
                }
                else {
                    cover.value = ''; // Reset cover if none found
                }

                isLoading.value = false;
                isSearchOk.value = true;
                isAllDone.value = true;
            }
            else {
                // Search API
                response = await fetch(`https://openlibrary.org/search.json?q=${search.value}&lang=en`);

                if(!response.ok){
                    throw new Error("Could not fetch resources, Search API");
                }

                data = await response.json();
                isLoading.value = false;
                isSearchOk.value = true;

                bookInfo.value = data.docs[0]; // Get all the info of the book
                bookID.value = bookInfo.value.key; // Get the ID of the book

                response = await fetch(`https://openlibrary.org${bookInfo.value.key}.json`);

                if(!response.ok){
                    throw new Error("Could not fetch resources, Search API");
                }

                data = await response.json()

                description.value = data.description;

                isAllDone.value = true;
            }
        } catch (error) {
            // Reset all the values if the search is not ok
            isSearchOk.value = false;
            isLoading.value = false;

            console.error(error);
        }
    }

</script>

<template>
    <main>
        <div class="input-box">
            <input type="button" value="Search random book" @click="setSearchType_random(); getBooks()">
            <input type="text" v-model="search" @keyup.enter="setSearchType_search(), sendBook()" placeholder="Search a book...">
        </div>

        <!-- Info box is shown only after the first successful  -->
        <div v-if="(isSearchOk && bookInfo.key) && isAllDone " class="info-book">
            <!-- Work API - Author API - Cover API -->
            <div class="book" v-if="isRandom" >
                <img :src="cover || '/book_cover.jpg'" alt="">

                <div class="info">
                    <h1>{{ bookInfo.title }}</h1>
                    <h2>-{{ authorInfo.name }}</h2>
                </div>

                <div class="description">
                    <!-- Description are not always an object and not always available -->
                    <p v-if="bookInfo.description && typeof bookInfo.description === 'object'">
                        {{ bookInfo.description.value }}
                    </p>
                    <p v-else-if="bookInfo.description && typeof bookInfo.description === 'string'">
                        {{ bookInfo.description }}
                    </p>
                    <p v-else>
                        Description is not available for this book right now, please try another time.
                    </p>
                </div>

            </div>

            <!-- Search API -->
            <div class="book" v-if="!isRandom">
                <img :src="`https://covers.openlibrary.org/b/id/${bookInfo.cover_i}-L.jpg`" :alt="bookInfo.title">

                <div class="info">
                    <h1>{{ bookInfo.title }}</h1>
                    <h2>-{{ bookInfo.author_name ? bookInfo.author_name[0] : 'Unknown Author'  }}</h2>
                </div>

                <div class="description">
                    <!-- Description are not always an object and not always available -->
                    <p v-if="description && typeof description === 'object'">
                        {{ description.value }}
                    </p>
                    <p v-else-if="description && typeof description === 'string'">
                        {{ description }}
                    </p>
                    <p v-else>
                        Description is not available for this book right now, please try another time.
                    </p>
                </div>
            </div>
        </div>

        <p v-if="!isSearchOk">No results</p>
        <p v-if="isLoading" >Loading...</p>
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

    .input-box {
        width: 50%;
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }

    input[type=button]{
        background-color: rgb(219, 48, 48);
        cursor: pointer;
        box-shadow: 0px 0px 10px rgb(255, 45, 45);
        transition: all 1s ease-in-out;
    }

    input[type=button]:hover {
        box-shadow: 0px 0px 65px rgb(255, 45, 45);
    }

    input {
        width: 20vw;
        height: 7.5vh;
        border-radius: 2vw / 2vw;
        padding-left: 1%;
        font-size: 1.5vw;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    ::placeholder {
        text-align: center;
    }

    .info-book {
        width: 100%;
        height: 100vh;
    }

    .book {
        width: 100%;
        height: 100%;
        background-color: #f7e5f6;
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
        width: 25vw;
        float: left
    }

    .info {
        min-width: 60%;
        position: absolute;
        z-index: 1;
        margin: 5% 1% 5% 10%;
        background-color: ghostwhite;
    }

    .info h2 {
        float: right;
    }

    .description {
        width: 70%;
        float: left;
        margin: 15% 0 0 2%;
    }
</style>
