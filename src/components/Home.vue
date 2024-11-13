<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        title: { type: String, default: "Title" },
        author: { type: String, default: "Author" },
        cover: { type: String, default: "/book_cover.jpg" },
        description: { type: String, default: "Description" },
        rating: { type: Number, default: 0 }
    });

    // Check if data is loaded, used to show the html
    const is_data_loaded = computed(() => {
        return props.title !== "Title";
    });
</script>

<template>
     <main class="book" v-if="is_data_loaded">
        <div class="book-info-wrapper">
            <img class="book-cover" :src="cover || '/book_cover.jpg'" alt="Cover"> 

            <div class="book-info">
                <h1>{{ title || "Title" }}</h1>
                <h2>{{ author || "Author" }}</h2>

                <ul class="stars">
                    <li img v-for="star in rating" :key="star"><img src="/icons/star.svg" alt="Star Rating"></li>
                </ul>
            </div>
        </div>

        <div v-html="description || 'Description'" class="description" ></div>
    </main>
</template>

<style scoped>
    .book {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .book-info-wrapper {
        width: 100%;
        display: flex;
        justify-content: space-evenly;
    }

    .book-cover {
        width: 15vw;
        height: 42vh;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .book-info {
        width: 40%;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5vh 0;
    }

    .stars {
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 2vw;
        list-style-type: none;
    }

    .stars img {
        width: 2vw;
    }

    .description {
        width: 80%;
        margin: 2% 0 2% 0;
        text-align: center;
    }

    @media only screen and (max-width: 600px) {
        h1 {
            font-size: 7vw;

        }
        
        .book-info-wrapper {
            flex-direction: column;
            align-items: center;
        }

        .book-cover {
            width: auto;
        }

        .book-info {
            width: 90%;
            gap: 2vh 0;
        }

        .stars img {
            width: 8vw;
        }

    }
    
    
    @media only screen and (min-width: 700px) and (max-width: 1024px) and (min-height: 700px){
        
    }
</style>