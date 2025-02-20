import type { APIContext, APIRoute } from 'astro';

export const POST: APIRoute = async ({
    request,
    session,
}: APIContext): Promise<Response> => {
    const formData = await request.json();

    const userToken: string | undefined = await session?.get('token');

    const response: Response = await fetch(
        'http://localhost:8000/api/account',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                token: userToken as string,
            },
            body: JSON.stringify(formData),
        },
    );

    const res = await response.json();
    if (response.status == 400) {
        return new Response(
            JSON.stringify({
                status: 'error',
                message: res.errors.amount[0],
            }),
            { status: 400 },
        );
    }

    return new Response(
        JSON.stringify({
            status: 'ok',
            message: res.message,
            balance: res.balance,
        }),
    );
};

export const GET: APIRoute = async ({
    request,
}: APIContext): Promise<Response> => {
    const userToken: string | null = await request?.headers.get('token');

    const response: Response = await fetch(
        'http://localhost:8000/api/balance',
        {
            method: 'GET',
            headers: {
                token: userToken as string,
            },
        },
    );

    return new Response(
        JSON.stringify({
            status: 'ok',
            balance: (await response.json()).balance,
        }),
    );
};
