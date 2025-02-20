import type { APIRoute } from 'astro';

export const POST: APIRoute = async ({ request, redirect }) => {
    const formData = await request.json();

    const token = formData['token'];

    const response = await fetch(
        `http://localhost:8000/api/reset-password/${token}`,
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        },
    );

    if (!response.ok) {
        return new Response(
            JSON.stringify({
                status: 'error',
                message: (await response.json()).message,
            }),
            { status: 401 },
        );
    }

    return redirect('/login');
};
