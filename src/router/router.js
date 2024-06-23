import { createRouter, createWebHistory } from "vue-router";
import Home from '../views/Home.vue';
import Book from '../views/Book.vue';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },

        {
            path: "/book/:id",
            name: "book",
            component: Book
        }
    ]
});

export default router;