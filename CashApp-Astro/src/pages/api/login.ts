import type {APIRoute} from "astro";

export const POST: APIRoute = async ({request, cookies, redirect}) => {
    const formData = await request.json();

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

    if (response.status === 401) {
        return new Response(JSON.stringify({
            status: 'error',
            message: 'Invalid credentials.',
        }), {status: 401});
    }

    const {user, token} = await response.json();
    localStorage.setItem("user", user);
    cookies.set("authToken", token, {
        httpOnly: true,
        secure: true,
        path: "/",
        maxAge: 60 * 60 * 24,
    });

    return redirect("/account");
}