package middleware

import (
	"crypto/subtle"

	"github.com/gofiber/fiber/v3"
)

func KunciApi(apiKey string) fiber.Handler {
	return func(c fiber.Ctx) error {
		if apiKey == "" {
			return fiber.NewError(fiber.StatusInternalServerError, "API_KEY belum dikonfigurasi")
		}
		if subtle.ConstantTimeCompare([]byte(c.Get("X-Api-Key")), []byte(apiKey)) != 1 {
			return fiber.NewError(fiber.StatusUnauthorized, "API key tidak valid")
		}
		return c.Next()
	}
}
