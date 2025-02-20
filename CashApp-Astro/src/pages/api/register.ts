import type { APIContext, APIRoute } from 'astro';

interface RegisterFormData {
    name: string;
    email: string;
    password: string;
}

export const POST: APIRoute = async ({
    request,
    redirect,
}: APIContext): Promise<Response> => {
    const formData: RegisterFormData = await request.json();

    const response: Response = await fetch(
        'http://localhost:8000/api/register',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        },
    );

    if (response.status === 401) {
        return new Response(
            JSON.stringify({
                status: 'error',
                message: (await response.json()).message,
            }),
            { status: 401 },
        );
    }

    return redirect('/login?registered=true', 301);
};
