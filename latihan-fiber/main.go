package main

import (
	"fmt"

	"github.com/gofiber/fiber/v3"
)

func main() {
	app := fiber.New()

	app.Get("/", func(c fiber.Ctx) error {
		return c.JSON(fiber.Map{
			"message": "Press it then go block",
		})
	})

	app.Get("/api/mahasiswa/", func(c fiber.Ctx) error {
		return c.JSON(fiber.Map{
			"nama":  "Bintang Nugraha Putra",
			"NIM":   "H1H024045",
			"prodi": "Teknik Komputer",
		})
	})

	fmt.Println(app.Listen(":3000"))
}
