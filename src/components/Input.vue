<script setup>
    import { ref } from 'vue';
    const emit = defineEmits(['updateBook']);

    // General
    const search = ref("");
    const isLoading = ref(false);
    const random_topics = ["Programming", "Cooking", "Biography", "Action", "Fantasy", "Tech", "Mistery", "c language", "for dummies"];

    // Book data
    const book_data = ref({});
    const bookID = ref("");
    const title = ref("");
    const author = ref("");
    const cover = ref("");
    const description = ref("");
    const rating = ref("");

    // Helper function for fetching
    async function fetchResource(url) {
        try {
            const response = await fetch(url);

            if(!response.ok){
                throw new Error("Could not fetch resources")
            };

            return await response.json();
        } catch (error) {
            console.error(error);
            return null;
        }
    }

    // Helper function for setting book info and sending resources to parent
    async function setBook_info(book_data){
        const first_item = book_data.items[0].volumeInfo;

        bookID.value = book_data.items[0].id;
        title.value = first_item.title || "Title not found";
        author.value = first_item.authors && first_item.authors.length > 0 ? first_item.authors[0] : "Author not found";
        description.value = first_item.description || "Description not found";
        rating.value = Math.floor(first_item.averageRating) || 0;
        

        book_data.value = await fetchResource(`https://www.googleapis.com/books/v1/volumes/${bookID.value}`);

        cover.value = book_data.value.volumeInfo.imageLinks.extraLarge ||
            book_data.value.volumeInfo.imageLinks.large ||
            book_data.value.volumeInfo.imageLinks.medium ||
            "/book_cover.jpg";
        

        // Send resources to parent component
        emit('updateBook', {
            title: title.value,
            author: author.value,
            cover: cover.value,
            description: description.value,
            rating: rating.value
        });
    }

    // Send search to back end
    async function sendBook() {
        try {
            const response = await fetch(`http://localhost/Book-Finder/backend/index.php?book=${encodeURIComponent(search.value)}`);

            if(!response.ok){
                throw new Error("Could not fetch resources")
            };

        } catch (error) {
            console.error(error);
        }
    }

    // Random book function
    async function getRandomBook() {
        isLoading.value = true;

        // Fetch Resources
        book_data.value = await fetchResource(`https://www.googleapis.com/books/v1/volumes?q=${random_topics[Math.floor(Math.random() * random_topics.length - 1)]}&langRestrict=en`);
        
        setBook_info(book_data.value);

        isLoading.value = false;
    }

    // Searched book function
    async function getSearchedBook() {
        isLoading.value = true;
        sendBook();
        
        // Fetch Resources
        book_data.value = await fetchResource(`https://www.googleapis.com/books/v1/volumes?q=${search.value}&langRestrict=en`);

        setBook_info(book_data.value);
    
        isLoading.value = false;
    }


</script>

<template>
    <div class="input-box">
        <input type="button" value="Search random book" @click="getRandomBook" :disabled="isLoading">
        <div class="search-box">
            <input type="text" v-model="search" @keyup.enter="getSearchedBook" placeholder="Search a book..." :disabled="isLoading">
            <img @click="getSearchedBook" :disabled="isLoading" src="/icons/search.svg" alt="search">
        </div>
    </div>
    
</template>

<style scoped>
    .input-box {
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
        margin: 3% 0 5% 0;
    }

    .search-box {
        display: flex;
    }

    input[type=button] {
        background-color: transparent;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    input[type=button]:hover {
        box-shadow: 0px 0px 3px 1px rgb(177, 172, 172);
    }

    input[type=text] {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: 0;
        padding-left: 7%;
    }

    input[type=text]:focus-visible {
        outline-width: 0;
        border-right: 0;
    }

    .search-box img {
        width: 2vw;
        background-color: white;
        border: 1px solid black;
        border-left: 0;
        border-top-right-radius: 2vw;
        border-bottom-right-radius: 2vw;
        padding-right: 6%;
    }

    @media only screen and (max-width: 600px) {
        .search-box img {
            width: 4vw;
        }
    }
    
    @media only screen and (min-width: 700px) and (max-width: 1024px) and (min-height: 700px){
        .search-box img {
            width: 5vw;
        }
    }
</style>
