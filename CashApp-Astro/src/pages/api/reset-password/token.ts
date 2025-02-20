import type { APIContext, APIRoute } from 'astro';

interface TokenFormData {
    token: string;
}

export const POST: APIRoute = async ({
    request,
    redirect,
}: APIContext): Promise<Response> => {
    const formData: TokenFormData = await request.json();

    const token: string = formData['token'];

    const response: Response = await fetch(
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
