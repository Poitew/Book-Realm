<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute, RouterLink } from 'vue-router';

    const route = useRoute();
    const bookID = ref(route.params.id);
    const bookInfo = ref({});

    onMounted(() => {
        fetch(`https://www.googleapis.com/books/v1/volumes?q=${bookID.value}`)
        .then(response => {
            if(!response.ok){
                throw new error("Could not fetch resources");
            }
            return response.json();
        })
        .then(data => {
            bookInfo.value = data.items[0].volumeInfo;
        })
        .catch( error => console.error(error));
    });
</script>

<template>
    <main v-if="bookInfo.title">
    
        <img :src="bookInfo.imageLinks.thumbnail" alt="">

        <div class="book-info">
            <h1>{{ bookInfo.title }} - {{ bookInfo.authors[0] }}</h1>

            <p>{{ bookInfo.description }}</p>

            <div class="buttons">
                <a :href="bookInfo.previewLink" target="_blank">
                    <div class="button">
                        <p>Preview Now!</p>
                    </div>
                </a>

                <RouterLink to="/">
                    <div class="button main-page-button">
                        <p>Return to main page</p>
                    </div>
                </RouterLink>
            </div>
        </div>
        
    </main>
</template>

<style setup>
    main {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        padding-top: 5%;
    }

    img {
        width: 15vw;
    }

    .book-info {
        width: 50%;
        display: flex;
        align-items: center;
        flex-direction: column;
        text-align: center;
        gap: 2vw;
    }

    .buttons {
        display: flex;
        gap: 2vw;
    }

    .button {
        width: 10vw;
        height: 3vw;
        background-color: rgb(43, 186, 43);
        border: 2px solid green;
        border-radius: 0.75vw / 0.75vw;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .main-page-button {
        background-color: rgb(186, 43, 43);
        border: red;
    }

</style>