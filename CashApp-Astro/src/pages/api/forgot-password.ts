import type { APIRoute } from 'astro';

export const POST: APIRoute = async ({ request }) => {
    const formData = await request.json();

    const response = await fetch('http://localhost:8000/api/reset-password', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
    });

    if (!response.ok) {
        return new Response(
            JSON.stringify({
                status: 'error',
                message: (await response.json()).message,
            }),
            { status: 401 },
        );
    }

    return new Response(
        JSON.stringify({
            status: 'ok',
            message: (await response.json()).message,
        }),
        { status: 200 },
    );
};
