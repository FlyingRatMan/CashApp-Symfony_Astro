import type { APIRoute } from 'astro';

export const GET: APIRoute = async ({ redirect, session }) => {
    const response = await fetch('http://localhost:8000/logout', {
        method: 'GET',
    });

    session?.set('token', null);
    session?.set('user', null);

    return redirect('/login');
};
