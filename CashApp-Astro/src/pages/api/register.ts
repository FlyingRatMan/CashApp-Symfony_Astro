import type {APIRoute} from "astro";


export const POST: APIRoute = async ({request, redirect}) => {
    const formData = await request.json();

    const data = {
        name: formData.name,
        email: formData.email,
        password: formData.password,
    };

    const response = await fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });

    const json = await response.json();

    if (response.status === 401) {
        return new Response(JSON.stringify({
            status: 'error',
            message: json.message || 'Astro: Something went wrong',
        }), {status: 401});
    }

    return redirect("/login?registered=true", 301);
    /*return new Response(JSON.stringify({
        status: 'success',
        message: 'Astro: Successfully registered',
    }), {status: 200});*/
}

// catch symfony response here, pass it to astro page