<template>
    <header>
        <h1>Books Finder <span>- Find your next read in a second!</span></h1>
    </header>
</template>

<style scoped>
    header {
        padding: 1.5% 0 0 5%;
    }

    h1 {
        font-size: 5vw;
        font-weight: 100;
    }

    h1 span {
        font-size: 2.5vw;
        font-weight: 200;
    }

    /* X-Small devices (portrait phones, less than 600px) */
    @media only screen and (max-width: 600px) {
        h1 {
            font-size: 12vw;
        }

        h1 span {
            font-size: 7vw;
        }
    }
    
    
    /* Medium devices (tablets, less than 1025) */
    @media only screen and (min-width: 700px) and (max-width: 1024px) and (min-height: 700px){
        h1 {
            font-size: 10vw;
        }

        h1 span {
            font-size: 5vw;
        }
    }
</style>