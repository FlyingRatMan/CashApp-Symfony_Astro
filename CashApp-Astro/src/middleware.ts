/*
import {defineMiddleware} from "astro/middleware";

export const onRequest = defineMiddleware(async (context, next) => {
    const token = context.cookies.get("authToken");

    if (token) {
        const formData = await context.request.json();

        const data = {
            email: formData.email,
            password: formData.password,
        };

        const response = await fetch('http://localhost:8000/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (response.ok) {
            const {user} = await response.json();
            localStorage.setItem("user", user);
            return;
        } else {

        }
    }
}
*/
