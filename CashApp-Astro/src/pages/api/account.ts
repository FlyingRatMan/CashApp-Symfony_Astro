import type { APIRoute } from 'astro';

export const POST: APIRoute = async ({ request, session, redirect }) => {
    const formData = await request.json();

    const data = {
        username: formData.email,
        password: formData.password,
    };

    const userToken = await session?.get('token');

    const response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: {
            token: userToken,
        },
    });

    if (response.status === 401) {
        return new Response(
            JSON.stringify({
                status: 'error',
                message: 'Invalid credentials.',
            }),
            { status: 401 },
        );
    }

    const { user, token } = await response.json();

    session?.set('token', token);
    session?.set('user', user);

    return redirect('/account');
};
