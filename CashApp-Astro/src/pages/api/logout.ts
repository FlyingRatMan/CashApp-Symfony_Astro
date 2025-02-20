import type { APIContext, APIRoute } from 'astro';

export const POST: APIRoute = async ({
    redirect,
    session,
}: APIContext): Promise<Response> => {
    const response: Response = await fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ token: await session?.get('token') }),
    });

    if (!response.ok) {
        return new Response(
            JSON.stringify({
                status: 'error',
                message: 'Something went wrong.',
            }),
            { status: 401 },
        );
    }

    session?.set('token', null);
    session?.set('user', null);

    return redirect('/login');
};
