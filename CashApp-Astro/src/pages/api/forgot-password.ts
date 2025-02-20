import type { APIContext, APIRoute } from 'astro';

interface ForgotPasswordFromData {
    email: string;
}

export const POST: APIRoute = async ({
    request,
}: APIContext): Promise<Response> => {
    const formData: ForgotPasswordFromData = await request.json();

    const response: Response = await fetch(
        'http://localhost:8000/api/reset-password',
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

    return new Response(
        JSON.stringify({
            status: 'ok',
            message: (await response.json()).message,
        }),
        { status: 200 },
    );
};
