import type { APIContext, APIRoute } from 'astro';

interface LoginFormData {
    email: string;
    password: string;
}

export const POST: APIRoute = async ({
    request,
    session,
    redirect,
}: APIContext): Promise<Response> => {
    const formData: LoginFormData = await request.json();

    const data = {
        username: formData.email,
        password: formData.password,
    };

    const response: Response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
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
